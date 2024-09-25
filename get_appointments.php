<?php
// Include database connection
include("connection.php");

// Initialize variables for storing appointments
$appointments = [];

// Check if the patient name is provided
if (isset($_POST['patient_name'])) {
    $patient_name = $_POST['patient_name'];

    // Prepare the SQL query to fetch patient_id based on the name
    $query_patient = "SELECT patient_id FROM patients WHERE name = ?";
    
    if ($stmt_patient = $con->prepare($query_patient)) {
        $stmt_patient->bind_param("s", $patient_name);
        $stmt_patient->execute();
        $result_patient = $stmt_patient->get_result();

        if ($result_patient && $result_patient->num_rows > 0) {
            $patient = $result_patient->fetch_assoc();
            $patient_id = $patient['patient_id'];

            // Prepare the SQL query to fetch appointments for the given patient_id
            $query_appointments = "SELECT a.serial_number, a.counselor_name, a.appointment_date, a.message 
                                   FROM appointments a 
                                   WHERE a.patient_id = ?";

            if ($stmt_appointments = $con->prepare($query_appointments)) {
                $stmt_appointments->bind_param("i", $patient_id);
                $stmt_appointments->execute();
                $result_appointments = $stmt_appointments->get_result();

                if ($result_appointments && $result_appointments->num_rows > 0) {
                    while ($row = $result_appointments->fetch_assoc()) {
                        $appointments[] = $row; // Store appointments in an array
                    }
                } else {
                    echo "<p>No appointments found for this patient.</p>";
                }

                // Close the statement
                $stmt_appointments->close();
            }
        } else {
            echo "<p>No patient found with this name.</p>";
        }

        // Close the patient statement
        $stmt_patient->close();
    } else {
        echo "<p>Error preparing statement: " . $con->error . "</p>";
    }
} else {
    echo "<p>Please enter your name to check appointments.</p>";
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
        form {
            margin-bottom: 20px;
            text-align: center;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="text"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 80%;
            max-width: 300px;
            margin: 0 auto;
        }
        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0056b3;
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Check Your Appointments</h2>
        <form method="POST" action="">
            <label for="patient_name">Enter Your Name:</label>
            <input type="text" id="patient_name" name="patient_name" required>
            <button type="submit">Check Appointments</button>
        </form>
        <?php if (!empty($appointments)): ?>
            <table>
                <tr>
                    <th>Serial Number</th>
                    <th>Counselor Name</th>
                    <th>Appointment Date</th>
                    <th>Message</th>
                </tr>
                <?php foreach ($appointments as $appointment): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($appointment['serial_number']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['counselor_name']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['appointment_date']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['message']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No appointments available.</p>
        <?php endif; ?>

        <!-- Home Button -->
        <div class="home-btn">
            <a href="actions.php"><button>Home</button></a>
        </div>
    </div>
</body>
</html>
