<?php
// Include database connection
include("connection.php");

// Initialize an empty appointments array
$appointments = [];

// Fetch all appointments from the appointments table
$query = "SELECT serial_number, patient_name, counselor_name, appointment_date FROM appointments";
$result = mysqli_query($con, $query);

// Error handling for query execution
if (!$result) {
    die("Query failed: " . mysqli_error($con)); // Output error if query fails
}

// Check if appointments are found
if ($result && mysqli_num_rows($result) > 0) {
    // Fetch and store appointments data in an array
    while ($row = mysqli_fetch_assoc($result)) {
        $appointments[] = $row;
    }
} else {
    // No appointments found
    echo "No appointments found.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Appointments</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .appointment {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .appointment h3 {
            margin-top: 0;
            color: #333;
        }

        .appointment p {
            margin-bottom: 10px;
            color: #666;
        }

        .appointment button {
            padding: 10px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .appointment button:hover {
            background-color: #c82333;
        }

    </style>
</head>
<body>
    <div class="container">
        <h1>All Appointments</h1>
        <?php
        // Display appointments if found
        if (!empty($appointments)) {
            foreach ($appointments as $appointment) {
                echo '<div class="appointment">';
                echo '<div>';
                echo '<h3>' . htmlspecialchars($appointment['counselor_name']) . '</h3>';
                echo '<p>Patient Name: ' . htmlspecialchars($appointment['patient_name']) . '</p>';
                echo '<p>Appointment Date: ' . htmlspecialchars($appointment['appointment_date']) . '</p>';
                echo '</div>';
                
                // Delete button with a form
                echo '<form method="POST" action="delete_appointments.php" style="margin: 0;">'; // Updated here
                echo '<input type="hidden" name="serial_number" value="' . htmlspecialchars($appointment['serial_number']) . '">';
                echo '<button type="submit">Delete</button>';
                echo '</form>';
                
                echo '</div>';
            }
        } else {
            echo '<p>No appointments are taken.</p>';
        }
        ?>
    </div>
</body>
</html>
