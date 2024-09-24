<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(!isset($_SESSION['user_id'])) {
    // If the user is not logged in, redirect them to the login page
    header("Location: login.php");
    exit;
}

// Include database connection
include("connection.php");

$user_id = $_SESSION['user_id'];

// Retrieve user's upcoming appointments
$query = "SELECT * FROM appointments WHERE user_id = '$user_id'";
echo $query; // Print the query for debugging purposes

$result = mysqli_query($con, $query);

// Display appointments
echo "<h2>Your Upcoming Appointments</h2>";
echo "<ul>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "<li>{$row['date']} at {$row['time']} with {$row['name_of_the_counselor']}</li>";
}
echo "</ul>";

mysqli_close($con);
?>
