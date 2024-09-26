<?php
include("connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $serial_number = $_POST['serial_number'];

    $query = "DELETE FROM appointments WHERE serial_number = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $serial_number);

    if ($stmt->execute()) {
        header("Location: admin_appointmentsview.php"); 
        exit();
    } else {
        echo "Error deleting appointment: " . $stmt->error;
    }

    $stmt->close();
}

mysqli_close($con);
?>
