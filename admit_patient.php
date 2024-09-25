<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "login_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error_message = "";
$success_message = "";
$stmt = null;  // Initialize $stmt to null

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_name = $_POST['patient_name'] ?? ''; // Use null coalescing operator
    $room_no = $_POST['room_no'] ?? ''; // Use null coalescing operator
    $room_type = $_POST['room_type'] ?? ''; // Use null coalescing operator
    $age = $_POST['age'] ?? 0; // Use null coalescing operator

    // Validate room number and room type
    if (($room_type == "AC" && ($room_no < 100 || $room_no > 150)) || ($room_type == "Non-AC" && ($room_no < 151 || $room_no > 200))) {
        $error_message = "Invalid room number for the selected room type. Please select an AC room number between 100-150 or a Non-AC room number between 151-200.";
    } else {
        // Check if the patient already exists in the database
        $stmt = $conn->prepare("SELECT patient_id FROM patients WHERE name = ?");
        $stmt->bind_param("s", $patient_name);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            // Fetch the patient ID
            $stmt->bind_result($patient_id);
            $stmt->fetch();
            
            // Check if the patient is already admitted to a room
            $stmt = $conn->prepare("SELECT room_no FROM rooms WHERE patient_id = ?");
            $stmt->bind_param("i", $patient_id);
            $stmt->execute();
            $stmt->store_result();
            
            if ($stmt->num_rows > 0) {
                $error_message = "Patient is already admitted to a room!";
            } else {
                // Check if the room is already booked
                $stmt = $conn->prepare("SELECT room_no FROM rooms WHERE room_no = ?");
                $stmt->bind_param("s", $room_no);
                $stmt->execute();
                $stmt->store_result();
                
                if ($stmt->num_rows > 0) {
                    $error_message = "Room already booked!";
                } else {
                    // Admit the patient into the room
                    $stmt = $conn->prepare("INSERT INTO rooms (room_no, type, patient_id) VALUES (?, ?, ?)");
                    $stmt->bind_param("ssi", $room_no, $room_type, $patient_id);
                    if ($stmt->execute()) {
                        $success_message = "Patient admitted successfully!";
                    } else {
                        $error_message = "Error admitting patient: " . $stmt->error;
                    }
                }
            }
        } else {
            $error_message = "Please register the patient first.";
        }
    }

    if ($stmt) {
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admit Patient</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <h1>Admit Patient</h1>

        <!-- Display error or success message -->
        <?php if ($error_message): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <?php if ($success_message): ?>
            <p class="success"><?php echo $success_message; ?></p>
        <?php endif; ?>

        <form action="admit_patient.php" method="post">
            <label for="patient_name">Enter Patient Name:</label>
            <input type="text" id="patient_name" name="patient_name" required>

            <label for="age">Enter Age:</label>
            <input type="text" id="age" name="age" required>

            <label for="room_no">Enter Room Number:</label>
            <input type="text" id="room_no" name="room_no" required>

            <label for="room_type">Room Type:</label>
            <select id="room_type" name="room_type" required>
                <option value="">--Select Room Type--</option>
                <option value="AC">AC</option>
                <option value="Non-AC">Non-AC</option>
            </select>

            <input type="submit" value="Admit Patient">
        </form>

        <!-- Back Button -->
        <button onclick="window.history.back()" class="back-btn">Go Back</button>
    </div>

</body>
</html>

<style type="text/css">
    /* General body styling */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f3f4f6;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background: linear-gradient(135deg, #74ebd5, #ACB6E5); /* Subtle gradient background */
    }

    /* Main container styling */
    .container {
        max-width: 450px;
        background-color: #ffffff;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 12px 20px rgba(0, 0, 0, 0.2); /* Deeper shadow for more depth */
        text-align: center;
    }

    /* Heading styling */
    h1 {
        color: #2c3e50;
        font-weight: 700;
        margin-bottom: 30px;
        font-size: 2rem;
    }

    /* Styling form elements */
    form {
        display: flex;
        flex-direction: column;
        gap: 20px; /* Increase gap between elements */
    }

    /* Form groups */
    .form-group {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    /* Labels */
    label {
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    /* Input and Select boxes */
    input[type="text"],
    select {
        width: 100%;
        padding: 12px;
        border: 1px solid #dcdfe6;
        border-radius: 8px;
        box-sizing: border-box;
        font-size: 1rem;
        background-color: #f9f9f9;
        transition: border-color 0.3s ease;
    }

    /* Focus effect */
    input[type="text"]:focus,
    select:focus {
        border-color: #1abc9c;
        outline: none;
    }

    /* Submit button styling */
    input[type="submit"] {
        background-color: #1abc9c;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 12px;
        font-size: 1.2rem;
        cursor: pointer;
        transition: background-color 0.3s ease;
        width: 100%;
        margin-top: 20px;
    }

    /* Hover effect for button */
    input[type="submit"]:hover {
        background-color: #16a085;
    }

    /* Back button styling */
    .back-btn {
        background-color: #3498db; /* Blue color */
        color: white;
        border: none;
        border-radius: 8px;
        padding: 12px;
        font-size: 1.2rem;
        cursor: pointer;
        margin-top: 20px;
        transition: background-color 0.3s ease;
        width: 100%;
    }

    .back-btn:hover {
        background-color: #2980b9; /* Darker blue on hover */
    }

    /* Error message styling */
    .error {
        color: #e74c3c;
        text-align: center;
        margin-bottom: 15px;
        font-weight: bold;
    }

    /* Success message styling */
    .success {
        color: #2ecc71;
        text-align: center;
        margin-bottom: 15px;
        font-weight: bold;
    }

    /* Input placeholders */
    input[type="text"]::placeholder,
    select {
        color: #95a5a6;
    }

    /* Aesthetic enhancement for animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .container {
        animation: fadeIn ease 0.5s;
    }
</style>
