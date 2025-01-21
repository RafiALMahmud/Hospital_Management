<?php
include("connection.php"); // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the review ID to delete
    $review_id = $_POST['review_id'];

    // Prepare the delete query
    $query = "DELETE FROM reviews WHERE review_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $review_id);

    if ($stmt->execute()) {
        // Redirect back to the reviews page after deletion
        header("Location: review_d.php");
        exit();
    } else {
        echo "Error deleting review: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
mysqli_close($con);
?>
