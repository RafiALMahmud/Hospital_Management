<?php
session_start();

// Check if a message exists in the session (to handle success or error)
$show_success = false;
$patient_name = '';
$doctor_name = '';
$appointment_time = '';

if (isset($_SESSION['message'])) {
    if ($_SESSION['message_type'] === 'alert') {
        // Success case: extract details
        $patient_name = $_SESSION['patient_name'] ?? ''; // You can store patient_name in session during appointment booking
        $doctor_name = $_SESSION['doctor_name'] ?? '';  // Same for doctor_name
        $appointment_time = $_SESSION['appointment_time'] ?? '';  // Same for appointment_time
        $show_success = true; // Flag to show success details
    } else {
        // Error case: Set failure message
        $error_message = $_SESSION['message'];
    }

    // Clear session message after it has been displayed
    unset($_SESSION['message'], $_SESSION['message_type'], $_SESSION['patient_name'], $_SESSION['doctor_name'], $_SESSION['appointment_time']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment</title>
    <style>
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

        .alert {
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .alert.success {
            background-color: #d0f0c0; /* Light green */
            color: #333;
        }

        .alert.error {
            background-color: #f8d7da; /* Light red */
            color: #721c24;
        }

        .buttons {
            text-align: center;
            margin-top: 20px;
        }

        .buttons button {
            background-color: #8b4513;
            color: #fff;
            border: none;
            padding: 10px 20px;
            margin: 5px;
            cursor: pointer;
            font-size: 1rem;
            border-radius: 4px;
        }

        .buttons button:hover {
            background-color: #6b3e0e;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Appointment Status</h1>

        <?php if ($show_success): ?>
            <div class="alert success">
                <p><strong>Appointment booked successfully!</strong></p>
                <p><strong>Name:</strong> <?= htmlspecialchars($patient_name) ?></p>
                <p><strong>Doctor:</strong> <?= htmlspecialchars($doctor_name) ?></p>
                <p><strong>Appointment Time:</strong> <?= htmlspecialchars($appointment_time) ?></p>
                <p><strong>Fare:</strong> 1000</p> <!-- Fixed typo -->
            </div>
        <?php elseif (isset($error_message)): ?>
            <div class="alert error">
                <p><strong>Failed to book the appointment.</strong></p>
                <p><?= htmlspecialchars($error_message) ?></p>
            </div>
        <?php endif; ?>

        <div class="buttons">
            <button onclick="window.location.href='booker.php'">Book Another Appointment</button>
            <button onclick="window.location.href='actions.php'">Go to Actions</button>
        </div>
    </div>
</body>
</html>
