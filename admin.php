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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9fafc;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        h1 {
            color: #444;
            font-size: 28px;
            margin-bottom: 20px;
        }

        .nav {
            background-color: #d1e8ff; /* Light blue navigation bar */
            padding: 10px;
            text-align: center;
            border-radius: 12px;
            margin-bottom: 30px;
        }

        .nav a {
            color: #333;
            text-decoration: none;
            font-weight: bold;
            margin: 0 20px;
            font-size: 16px;
            padding: 10px 20px; /* Adjusted padding to make hover effect cover entire area */
            display: inline-block; /* Ensures anchor tag acts like a button */
            transition: color 0.3s ease, background-color 0.3s ease;
            border-radius: 8px; /* Added border radius for rounded hover */
        }

        .nav a:hover {
            color: #fff;
            background-color: #0056b3; /* Navy blue hover effect */
        }

        .welcome-message {
            font-size: 22px;
            margin-bottom: 30px;
            color: #3498db; /* Soft blue color */
        }

        .logout-btn {
            padding: 10px 20px;
            background-color: #e74c3c; /* Red color */
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .logout-btn:hover {
            background-color: #c0392b; /* Darker red on hover */
        }

        .action-btn {
            padding: 15px 20px;
            background-color: #003366; /* Navy blue color */
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            text-align: center;
            display: block;
            width: 100%;
            margin-bottom: 15px;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }

        .action-btn:hover {
            background-color: #002244; /* Darker navy blue on hover */
        }

        .action-container {
            margin-bottom: 30px;
        }

        /* Styling for input and button hover */
        button:focus, a:focus, input:focus {
            outline: none;
            box-shadow: 0 0 8px rgba(52, 152, 219, 0.4);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="review_d.php">Reviews</a>
            <a href="http://localhost/phpmyadmin/index.php" target="_blank">Manage</a>
            <a href="discharge.php">Discharge</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>

        <h1>Admin Dashboard</h1>
        <p class="welcome-message">Welcome to the Admin Dashboard, <?php echo htmlspecialchars($admin_data['name']); ?>!</p>

        <!-- Add Patient to Room Button (Updated to redirect to admit_patient.php) -->
        <div class="action-container">
            <a href="admit_patient.php" class="action-btn">Admit Patient</a>
        </div>

        <!-- Add Patient Button -->
        <div class="action-container">
            <a href="signup.php" class="action-btn">Add Patient</a>
        </div>
    </div>
</body>
</html>
