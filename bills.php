<?php
// Include database connection
include("connection.php");

// Start session and retrieve logged-in user's user_ID
session_start();
if (!isset($_SESSION['user_ID'])) {
    echo "<p>Please log in to view your bills.</p>";
    exit;
}
$user_ID = $_SESSION['user_ID'];

// Initialize variables for storing bills
$bills = [];

// Fetch the patient's ID and name based on user_ID
$query_patient = "SELECT patient_id, name FROM patients WHERE user_ID = ?";
if ($stmt_patient = $con->prepare($query_patient)) {
    $stmt_patient->bind_param("i", $user_ID);
    $stmt_patient->execute();
    $result_patient = $stmt_patient->get_result();

    if ($result_patient && $result_patient->num_rows > 0) {
        $patient = $result_patient->fetch_assoc();
        $patient_id = $patient['patient_id'];
        $patient_name = $patient['name'];

        // Fetch bills from the rooms table
        $query_rooms = "SELECT r.room_no, r.type AS room_type, r.admitted_at, r.discharge_time 
                        FROM rooms r 
                        WHERE r.patient_id = ? AND r.discharge_time IS NOT NULL";

        if ($stmt_rooms = $con->prepare($query_rooms)) {
            $stmt_rooms->bind_param("i", $patient_id);
            $stmt_rooms->execute();
            $result_rooms = $stmt_rooms->get_result();

            if ($result_rooms && $result_rooms->num_rows > 0) {
                while ($row = $result_rooms->fetch_assoc()) {
                    // Calculate the duration of stay
                    $admitted_at = new DateTime($row['admitted_at']);
                    $discharge_time = new DateTime($row['discharge_time']);
                    $duration = $admitted_at->diff($discharge_time);
                    $days = $duration->days;
                    $hours = $duration->h + ($days * 24); // Total hours

                    // Calculate the amount based on duration
                    if ($hours <= 24) {
                        $amount = ($row['room_type'] == 'AC') ? 10000 : 7000; // First day charge
                    } else {
                        $daily_rate = ($row['room_type'] == 'AC') ? 10000 : 7000;
                        $amount = $daily_rate * $days;
                        $partial_hours = $hours % 24;
                        if ($partial_hours > 0) {
                            $hourly_rate = ($row['room_type'] == 'AC') ? 550 : 400;
                            $amount += $hourly_rate * $partial_hours;
                        }
                    }

                    $row['amount'] = $amount; // Store calculated amount
                    $row['type'] = 'Room'; // Add type for identification
                    $row['name'] = $patient_name; // Include patient's name
                    $bills[] = $row; // Store bills in an array
                }
            }
            $stmt_rooms->close();
        }

        // Fetch bills from the appointments table
        $query_appointments = "SELECT COUNT(*) AS appointment_count 
                               FROM appointments 
                               WHERE patient_id = ?";

        if ($stmt_appointments = $con->prepare($query_appointments)) {
            $stmt_appointments->bind_param("i", $patient_id);
            $stmt_appointments->execute();
            $result_appointments = $stmt_appointments->get_result();

            if ($result_appointments && $result_appointments->num_rows > 0) {
                $row = $result_appointments->fetch_assoc();
                $appointment_count = $row['appointment_count'];
                if ($appointment_count > 0) {
                    $bills[] = [
                        'type' => 'Appointment',
                        'room_no' => 'N/A',
                        'room_type' => 'N/A',
                        'admitted_at' => 'N/A',
                        'discharge_time' => 'N/A',
                        'amount' => $appointment_count * 1000, // 1000 per appointment
                        'name' => $patient_name, // Include patient's name
                    ];
                }
            }
            $stmt_appointments->close();
        }
    } else {
        echo "<p>No patient information found for this user.</p>";
        exit;
    }
    $stmt_patient->close();
}

// Handle payment request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay_bill'])) {
    $bill_type = $_POST['bill_type'];
    $room_no = $_POST['room_no'];
    $amount = $_POST['amount'];

    // Disable foreign key checks temporarily
    mysqli_query($con, "SET foreign_key_checks = 0");

    // Insert into the bill table
    $query_insert_bill = "INSERT INTO bill (room_no, patient_id, bill_amount, bill_date) 
                          VALUES (?, ?, ?, NOW())";
    if ($stmt_insert_bill = $con->prepare($query_insert_bill)) {
        $room_no = ($room_no === 'N/A') ? NULL : $room_no; // Handle NULL for room_no
        $stmt_insert_bill->bind_param("iid", $room_no, $patient_id, $amount);
        $stmt_insert_bill->execute();
        $stmt_insert_bill->close();
    }

    // Remove the paid bill from the appropriate table
    if ($bill_type === 'Room') {
        // Remove the room from the rooms table completely
        $query_delete_room = "DELETE FROM rooms WHERE room_no = ? AND patient_id = ?";
        if ($stmt_delete_room = $con->prepare($query_delete_room)) {
            $stmt_delete_room->bind_param("ii", $room_no, $patient_id);
            $stmt_delete_room->execute();
            $stmt_delete_room->close();
        }
    } elseif ($bill_type === 'Appointment') {
        $query_delete_appointments = "DELETE FROM appointments WHERE patient_id = ?";
        if ($stmt_delete_appointments = $con->prepare($query_delete_appointments)) {
            $stmt_delete_appointments->bind_param("i", $patient_id);
            $stmt_delete_appointments->execute();
            $stmt_delete_appointments->close();
        }
    }

    // Enable foreign key checks again
    mysqli_query($con, "SET foreign_key_checks = 1");

    echo "<p>Bill paid successfully.</p>";
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
            background-color: #e9ecef;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            background-color: white;
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
        .home-btn {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Your Bills</h2>
        <?php if (!empty($bills)): ?>
            <table>
                <tr>
                    <th>Type</th>
                    <th>Room No</th>
                    <th>Room Type</th>
                    <th>Patient Name</th>
                    <th>Total Amount</th>
                    <th>Admitted At</th>
                    <th>Discharge Time</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($bills as $bill): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($bill['type']); ?></td>
                        <td><?php echo htmlspecialchars($bill['room_no']); ?></td>
                        <td><?php echo htmlspecialchars($bill['room_type']); ?></td>
                        <td><?php echo htmlspecialchars($bill['name']); ?></td>
                        <td><?php echo htmlspecialchars($bill['amount']); ?></td>
                        <td><?php echo htmlspecialchars($bill['admitted_at']); ?></td>
                        <td><?php echo htmlspecialchars($bill['discharge_time']); ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="bill_type" value="<?php echo htmlspecialchars($bill['type']); ?>">
                                <input type="hidden" name="room_no" value="<?php echo htmlspecialchars($bill['room_no']); ?>">
                                <input type="hidden" name="amount" value="<?php echo htmlspecialchars($bill['amount']); ?>">
                                <button type="submit" name="pay_bill">Pay</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No bills available.</p>
        <?php endif; ?>

        <div class="home-btn">
            <a href="actions.php"><button>Home</button></a>
        </div>
    </div>
</body>
</html>
