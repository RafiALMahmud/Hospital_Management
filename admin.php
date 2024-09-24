<?php
include("connection.php"); // Include your database connection file

// Directly check for admin_id in the admin table
$admin_id = 1; // Assuming this is the admin you're checking for

// Prepare and execute the query to fetch admin details
$query = "SELECT * FROM admin WHERE admin_id = ? LIMIT 1"; // Using 'admin_id' as primary key
$stmt = $con->prepare($query);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $admin_data = $result->fetch_assoc();
} else {
    echo "Admin not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
        }

        .nav {
            background-color: #007bff;
            padding: 10px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .nav a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            margin: 0 15px;
        }

        .nav a:hover {
            text-decoration: underline;
        }

        .welcome-message {
            font-size: 24px;
            margin-bottom: 20px;
            color: #007bff;
        }

        .logout-btn {
            padding: 10px 20px;
            background-color: #d9534f;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .logout-btn:hover {
            background-color: #c9302c;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="review_d.php">Reviews</a> <!-- Updated to redirect to reviews -->
            <a href="http://localhost/phpmyadmin/index.php" target="_blank">Manage</a> <!-- Manage link for PHPMyAdmin -->
            <a href="discharge.php">Discharge</a> <!-- Discharge link -->
            <a href="logout.php" class="logout-btn">Logout</a> <!-- Logout link -->
        </div>

        <h1>Admin Dashboard</h1>
        <p class="welcome-message">Welcome to the Admin Dashboard, <?php echo htmlspecialchars($admin_data['name']); ?>!</p> <!-- Assuming 'admin_name' exists -->
    </div>
</body>
</html>
