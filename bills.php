<?php
// Include database connection
include("connection.php");

// Initialize variables for storing bills
$bills = [];

// Check if the patient name is provided
if (isset($_POST['patient_name'])) {
    $patient_name = $_POST['patient_name'];

    // Prepare the SQL query to fetch the patient_id based on the name
    $query_patient = "SELECT patient_id FROM patients WHERE name = ?";
    
    if ($stmt_patient = $con->prepare($query_patient)) {
        $stmt_patient->bind_param("s", $patient_name);
        $stmt_patient->execute();
        $result_patient = $stmt_patient->get_result();

        if ($result_patient && $result_patient->num_rows > 0) {
            $patient = $result_patient->fetch_assoc();
            $patient_id = $patient['patient_id'];

            // Prepare the SQL query to fetch room details for the given patient_id
            $query_bills = "SELECT r.room_no, r.type AS room_type, p.name, r.admitted_at, r.discharge_time 
                            FROM rooms r 
                            JOIN patients p ON r.patient_id = p.patient_id 
                            WHERE r.patient_id = ?";

            if ($stmt_bills = $con->prepare($query_bills)) {
                $stmt_bills->bind_param("i", $patient_id);
                $stmt_bills->execute();
                $result_bills = $stmt_bills->get_result();

                if ($result_bills && $result_bills->num_rows > 0) {
                    while ($row = $result_bills->fetch_assoc()) {
                        // Calculate the duration of stay
                        $admitted_at = new DateTime($row['admitted_at']);
                        $discharge_time = !empty($row['discharge_time']) ? new DateTime($row['discharge_time']) : new DateTime(); // Current time if discharge_time is NULL
                        $duration = $admitted_at->diff($discharge_time);
                        $days = $duration->days;
                        $hours = $duration->h + ($days * 24); // Total hours

                        // Calculate the amount based on duration
                        if ($hours <= 24) {
                            $amount = ($row['room_type'] == 'AC') ? 10000 : 7000; // First day charge
                        } else {
                            // Calculate charges for full days
                            $daily_rate = ($row['room_type'] == 'AC') ? 10000 : 7000;
                            $amount = $daily_rate * $days;
                            // Check if there's a partial day
                            if ($hours % 24 > 0) {
                                $amount += ($row['room_type'] == 'AC') ? 10000 : 7000; // Add partial day charge
                            }
                        }

                        $row['amount'] = $amount; // Store calculated amount
                        $bills[] = $row; // Store bills in an array
                    }
                } else {
                    echo "<p>No room found for this patient.</p>";
                }

                // Close the statement
                $stmt_bills->close();
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
    echo "<p>Please enter your name to check bills.</p>";
}

// Handle bill payment
if (isset($_POST['pay_bill']) && isset($_POST['room_no'])) {
    $room_no = $_POST['room_no'];

    // Prepare the SQL query to delete the row from the rooms table
    $query_delete = "DELETE FROM rooms WHERE room_no = ?";
    
    if ($stmt_delete = $con->prepare($query_delete)) {
        $stmt_delete->bind_param("s", $room_no);
        if ($stmt_delete->execute()) {
            echo "<p>Bill paid successfully. Room number $room_no has been cleared.</p>";
        } else {
            echo "<p>Error paying bill: " . $con->error . "</p>";
        }
        $stmt_delete->close();
    }
}

// Close the connection
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Bills</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #e9ecef; /* Changed background color */
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            background-color: transparent; /* Removed white background */
        }
        h2 {
            color: #333;
            text-align: center; /* Centered the heading */
        }
        form {
            margin-bottom: 20px;
            text-align: center; /* Centered the form elements */
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
            width: 80%; /* Adjusted width */
            max-width: 300px; /* Maximum width for input */
            margin: 0 auto; /* Centered the input field */
        }
        button {
            padding: 10px 20px;
            background-color: #007bff; /* Primary button color */
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0056b3; /* Darker shade on hover */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px; /* Added margin for spacing */
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Check Your Bills</h2>
        <form method="POST" action="">
            <label for="patient_name">Enter Your Name:</label>
            <input type="text" id="patient_name" name="patient_name" required>
            <button type="submit">Check Bills</button>
        </form>
        <?php if (!empty($bills)): ?>
            <table>
                <tr>
                    <th>Room No</th>
                    <th>Room Type</th>
                    <th>Patient Name</th>
                    <th>Total Amount</th>
                    <th>Admitted At</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($bills as $bill): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($bill['room_no']); ?></td>
                        <td><?php echo htmlspecialchars($bill['room_type']); ?></td>
                        <td><?php echo htmlspecialchars($bill['name']); ?></td>
                        <td><?php echo htmlspecialchars($bill['amount']); ?></td>
                        <td><?php echo htmlspecialchars($bill['admitted_at']); ?></td>
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="room_no" value="<?php echo htmlspecialchars($bill['room_no']); ?>">
                                <button type="submit" name="pay_bill">Pay Bill</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No bills available.</p>
        <?php endif; ?>
    </div>
</body>
</html>
