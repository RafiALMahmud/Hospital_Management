<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch logged-in user's name and patient_id
$user_id = $_SESSION['user_ID'] ?? null;
$patient_name = '';
$patient_id = '';

if ($user_id) {
    $sql = "SELECT patient_id, name FROM patients WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $patient_name = $row['name'];
            $patient_id = $row['patient_id'];
        }
        $stmt->close();
    }
} else {
    $_SESSION['message'] = "Please log in to book an appointment.";
    $_SESSION['message_type'] = "alert error";
    header("Location: login.php");
    exit();
}

// Fetch data from the `doctors` table
$sql = "SELECT doctor_id, name, speciality FROM doctors";
$doctors_result = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone_number = $_POST['phone_number'] ?? '';
    $email = $_POST['email'] ?? '';
    $doctor_id = $_POST['doctor_id'] ?? '';
    $appointment_date = $_POST['appointment_date'] ?? '';
    $appointment_time = $_POST['appointment_time'] ?? '';
    $message = $_POST['message'] ?? '';

    // Calculate `end_time` by adding 30 minutes to `appointment_time`
    $appointment_time_obj = DateTime::createFromFormat('H:i', $appointment_time);
    $end_time_obj = clone $appointment_time_obj;
    $end_time_obj->modify('+30 minutes');
    $end_time = $end_time_obj->format('H:i');

    // Check doctor availability
    $sql_check = "
        SELECT * FROM appointments
        WHERE doctor_id = ?
        AND appointment_date = ?
        AND (
            (appointment_time < ? AND end_time > ?) OR
            (appointment_time < ? AND end_time > ?)
        )
    ";

    $stmt_check = $conn->prepare($sql_check);
    if ($stmt_check) {
        $stmt_check->bind_param(
            "isssss",
            $doctor_id,
            $appointment_date,
            $end_time,
            $appointment_time,
            $appointment_time,
            $end_time
        );
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check && $result_check->num_rows > 0) {
            $_SESSION['message'] = "The selected doctor is not available at the chosen time. Please select a different time.";
            $_SESSION['message_type'] = "alert error";
        } else {
            if (!empty($patient_id) && !empty($patient_name) && !empty($phone_number) && !empty($email) &&
                !empty($doctor_id) && !empty($appointment_date) && !empty($appointment_time)) {

                $sql = "INSERT INTO appointments (patient_id, patient_name, doctor_id, counselor_name, appointment_date, appointment_time, end_time, phone_number, message) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                if ($stmt) {
                    $stmt->bind_param(
                        "isissssss",
                        $patient_id,
                        $patient_name,
                        $doctor_id,
                        $counselor_name,
                        $appointment_date,
                        $appointment_time,
                        $end_time,
                        $phone_number,
                        $message
                    );

                    if ($stmt->execute()) {
                        $_SESSION['message'] = "Appointment booked successfully!";
                        $_SESSION['message_type'] = "alert";
                        $_SESSION['patient_name'] = $patient_name;
                        $_SESSION['doctor_name'] = $counselor_name;
                        $_SESSION['appointment_time'] = $appointment_time;
                    } else {
                        $_SESSION['message'] = "Failed to book the appointment. Please try again.";
                        $_SESSION['message_type'] = "alert error";
                    }
                    $stmt->close();
                }
            } else {
                $_SESSION['message'] = "Please fill in all required fields.";
                $_SESSION['message_type'] = "alert error";
            }
        }
        $stmt_check->close();
    }

    header("Location: book_appointment.php");
    exit();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment</title>
    <style>
        /* Message container styling */
        .message-container {
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, background-color 0.3s ease;
        }

        .message-container.success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .message-container.error {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        .message-container:hover {
            transform: scale(1.05);
            background-color: #f1f1f1;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f2e6;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
            color: #333;
        }

        input, select, textarea, button {
            width: 100%;
            margin-top: 5px;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #8b4513;
            color: #fff;
            border: none;
            margin-top: 20px;
            cursor: pointer;
        }

        button:hover {
            background-color: #6b3e0e;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Book an Appointment</h1>

        <!-- Message Section -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="message-container <?= $_SESSION['message_type'] ?>">
                <?= $_SESSION['message'] ?>
            </div>
            <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
        <?php endif; ?>

        <!-- Form Section -->
        <form method="POST" action="">
            <label for="phone_number">Phone Number:</label>
            <input type="text" id="phone_number" name="phone_number" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="doctor_id">Choose a Counselor:</label>
            <select id="doctor_id" name="doctor_id" required>
                <option value="">-- Select a Counselor --</option>
                <?php while ($doctor = $doctors_result->fetch_assoc()): ?>
                    <option value="<?= $doctor['doctor_id'] ?>">
                        <?= htmlspecialchars($doctor['name'] . " (" . $doctor['speciality'] . ")") ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="appointment_date">Appointment Date:</label>
            <input type="date" id="appointment_date" name="appointment_date" required>

            <label for="appointment_time">Appointment Time:</label>
            <input type="time" id="appointment_time" name="appointment_time" required>

            <label for="message">Message (Optional):</label>
            <textarea id="message" name="message"></textarea>

            <button type="submit">Book Appointment</button>
        </form>

        <button onclick="window.location.href='actions.php'">Back</button>
    </div>
</body>
</html>
