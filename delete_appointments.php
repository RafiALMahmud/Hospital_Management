<?php
// Include database connection
include("connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the serial_number to delete the appointment
    $serial_number = $_POST['serial_number'];

    // Prepare the delete query
    $query = "DELETE FROM appointments WHERE serial_number = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $serial_number);

    if ($stmt->execute()) {
        // Redirect back to the appointments page after deletion
        header("Location: admin_appointmentsview.php"); // Assuming this is the name of your file
        exit();
    } else {
        echo "Error deleting appointment: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
mysqli_close($con);
?>
