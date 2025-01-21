<?php
session_start();

// Include database connection
include("connection.php");

// Initialize variables for storing appointments
$appointments = [];

// Check if the user is logged in
if (isset($_SESSION['user_ID'])) {
    $user_id = $_SESSION['user_ID'];

    // Prepare the SQL query to fetch appointments for the logged-in user
    $query_appointments = "SELECT p.name AS patient_name, a.counselor_name, a.appointment_date, a.appointment_time, a.message 
                           FROM appointments a 
                           JOIN patients p ON a.patient_id = p.patient_id 
                           WHERE p.user_id = ?";

    if ($stmt_appointments = $con->prepare($query_appointments)) {
        $stmt_appointments->bind_param("i", $user_id);
        $stmt_appointments->execute();
        $result_appointments = $stmt_appointments->get_result();

        if ($result_appointments && $result_appointments->num_rows > 0) {
            while ($row = $result_appointments->fetch_assoc()) {
                $appointments[] = $row; // Store appointments in an array
            }
        } else {
            echo "<p>No appointments found for this user.</p>";
        }

        // Close the statement
        $stmt_appointments->close();
    } else {
        echo "<p>Error preparing statement: " . $con->error . "</p>";
    }
} else {
    echo "<p>User not logged in. Please log in to check appointments.</p>";
}

// Close the connection
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Appointments</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #e9ecef;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            background-color: transparent;
        }
        h2 {
            color: #333;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        .home-btn {
            margin-top: 20px;
            display: block;
            text-align: center;
        }
        .home-btn button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .home-btn button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Your Appointments</h2>
        <?php if (!empty($appointments)): ?>
            <table>
                <tr>
                    <th>Patient Name</th>
                    <th>Counselor Name</th>
                    <th>Appointment Date</th>
                    <th>Appointment Time</th>
                    <th>Message</th>
                </tr>
                <?php foreach ($appointments as $appointment): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($appointment['patient_name']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['counselor_name']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['appointment_date']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['appointment_time']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['message']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No appointments available.</p>
        <?php endif; ?>

        <!-- Home Button -->
        <div class="home-btn">
            <a href="index.php"><button>Home</button></a>
        </div>
    </div>
</body>
</html>
