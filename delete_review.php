<?php
include("connection.php"); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $review_id = $_POST['review_id'];

    $query = "DELETE FROM reviews WHERE review_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $review_id);

    if ($stmt->execute()) {
        header("Location: review_d.php");
        exit();
    } else {
        echo "Error deleting review: " . $stmt->error;
    }

    $stmt->close();
}

mysqli_close($con);
?>
