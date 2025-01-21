<?php
// Include database connection
include("connection.php");

// Initialize an array to hold patient data
$patients = [];

// Fetch existing patients in rooms
$query = "SELECT r.room_no, r.type AS room_type, p.name, r.patient_id 
          FROM rooms r 
          JOIN patients p ON r.patient_id = p.patient_id 
          WHERE r.patient_id IS NOT NULL";

if ($result = $con->query($query)) {
    while ($row = $result->fetch_assoc()) {
        $patients[] = $row; // Store patients in the array
    }
}

// Handle discharge action
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['discharge_id'])) {
    $patient_id = $_POST['discharge_id'];
    $discharge_time = date('Y-m-d H:i:s'); // Current timestamp

    // Update the discharge time in the rooms table
    $update_query = "UPDATE rooms SET discharge_time = ? WHERE patient_id = ?";
    
    if ($stmt = $con->prepare($update_query)) {
        $stmt->bind_param("si", $discharge_time, $patient_id);
        if ($stmt->execute()) {
            echo "<p>Patient discharged successfully.</p>";
        } else {
            echo "<p>Error discharging patient: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        echo "<p>Error preparing statement: " . $con->error . "</p>";
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
    <title>Discharge Patients</title>
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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
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
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Discharge Patients</h2>
        <?php if (!empty($patients)): ?>
            <table>
                <tr>
                    <th>Room No</th>
                    <th>Room Type</th>
                    <th>Patient Name</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($patients as $patient): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($patient['room_no']); ?></td>
                        <td><?php echo htmlspecialchars($patient['room_type']); ?></td>
                        <td><?php echo htmlspecialchars($patient['name']); ?></td>
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="discharge_id" value="<?php echo htmlspecialchars($patient['patient_id']); ?>">
                                <button type="submit">Discharge</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No patients available for discharge.</p>
        <?php endif; ?>
    </div>
</body>
</html>
