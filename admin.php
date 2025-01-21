<?php
session_start();
include("connection.php"); // Include your database connection file

// Check if admin_ID session exists
if (!isset($_SESSION['admin_ID'])) {
    header("Location: admin_login.php");
    exit;
}

// Retrieve admin_id from the session
$admin_id = intval($_SESSION['admin_ID']);

// Validate admin_id from the database
$query = "SELECT * FROM admin WHERE admin_id = ? LIMIT 1";
$stmt = $con->prepare($query);
if (!$stmt) {
    die("Database error: Failed to prepare statement.");
}
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $admin_data = $result->fetch_assoc();

    // Ensure the admin_id matches the authorized admin (admin_id = 1)
    if ($admin_id !== 1) {
        session_destroy();
        echo "<script>alert('Unauthorized access.'); window.location.href = 'admin_login.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('Admin not found. Please log in again.'); window.location.href = 'admin_login.php';</script>";
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
        /* Global Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #74ebd5, #acb6e5); /* Gradient background */
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #333;
        }

        .container {
            max-width: 1100px;
            width: 100%;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h1 {
            color: #2c3e50;
            font-size: 32px;
            margin-bottom: 20px;
            text-align: center;
        }

        .nav {
            background-color: #3498db; /* Primary blue navigation bar */
            padding: 10px;
            text-align: center;
            border-radius: 12px;
            margin-bottom: 30px;
        }

        .nav a {
            color: #ffffff;
            text-decoration: none;
            font-weight: bold;
            margin: 0 15px;
            font-size: 16px;
            padding: 10px 20px;
            display: inline-block;
            border-radius: 8px;
            transition: transform 0.3s ease, background-color 0.3s ease;
        }

        .nav a:hover {
            background-color: #2980b9;
            transform: scale(1.1);
        }

        .welcome-message {
            font-size: 22px;
            margin-bottom: 30px;
            color: #16a085;
            text-align: center;
        }

        .dropdown-btn {
            padding: 15px 20px;
            background: linear-gradient(135deg, #6a11cb, #2575fc); /* Gradient button color */
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            text-align: center;
            width: 100%;
            text-decoration: none;
            font-weight: bold;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .dropdown-btn:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .dropdown-content {
            display: none; /* Hidden by default */
            flex-direction: column;
            margin-top: 20px;
        }

        .dropdown-content a {
            padding: 15px 20px;
            background-color: #f4f4f4;
            color: #333;
            text-decoration: none;
            font-weight: bold;
            border-radius: 8px;
            margin-bottom: 10px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .dropdown-content a:hover {
            background-color: #ddd;
            transform: translateX(10px);
        }

        .logout-btn {
            padding: 10px 20px;
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .logout-btn:hover {
            background-color: #c0392b;
            transform: translateY(-4px);
        }
    </style>
    <script>
        function toggleDropdown() {
            const dropdownContent = document.getElementById('dropdown-content');
            if (dropdownContent.style.display === 'flex') {
                dropdownContent.style.display = 'none';
            } else {
                dropdownContent.style.display = 'flex';
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="review_d.php">Reviews</a>
            <a href="discharge.php">Discharge</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>

        <h1>Admin Dashboard</h1>
        <p class="welcome-message">
            Welcome to the Admin Dashboard, <?php echo htmlspecialchars($admin_data['name']); ?>!
        </p>

        <!-- Dropdown Menu -->
        <button class="dropdown-btn" onclick="toggleDropdown()">Features / Options</button>
        <div id="dropdown-content" class="dropdown-content">
            <a href="admit_patient.php">Admit Patient</a>
            <a href="signup.php">Add Patient</a>
            <a href="admin_appointmentsview.php">Manage Appointments</a>
            <a href="add_doc.php">Add Doctor</a>
            <a href="index.php">Patient Dashboard</a>
        </div>
    </div>
</body>
</html>
