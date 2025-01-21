<?php
session_start();
if (!isset($_SESSION['admin_ID'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $user_type = 'Doctor';
    $address = $_POST['address'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $speciality = $_POST['speciality'];

    $conn = new mysqli('localhost', 'root', '', 'login_db');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO users (user_name, password, user_type) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $password, $user_type);
    $stmt->execute();
    $user_id = $stmt->insert_id;
    $stmt->close();

    $stmt = $conn->prepare("INSERT INTO doctors (user_id, name, address, email, phone_number, speciality) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $user_id, $name, $address, $email, $phone_number, $speciality);
    $stmt->execute();
    $stmt->close();

    $conn->close();

    $success_message = "Doctor added successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Doctor</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        input[type="text"]:hover,
        input[type="password"]:hover,
        input[type="email"]:hover,
        textarea:hover {
            border-color: #007bff;
        }

        input[type="submit"] {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            border: 1px solid #c3e6cb;
        }

        .back-btn {
            text-align: center;
            margin-top: 20px;
        }

        .back-btn a {
            text-decoration: none;
            color: white;
            background-color: #007bff;
            padding: 10px 20px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .back-btn a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Add Doctor</h1>

        <?php if (isset($success_message)): ?>
            <div class="success-message">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <form action="add_doc.php" method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br><br>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>
            
            <label for="address">Address:</label>
            <textarea id="address" name="address" required></textarea><br><br>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br><br>
            
            <label for="phone_number">Phone Number:</label>
            <input type="text" id="phone_number" name="phone_number" required><br><br>
            
            <label for="speciality">Speciality:</label>
            <input type="text" id="speciality" name="speciality" required><br><br>
            
            <input type="submit" value="Add Doctor">
        </form>

        <div class="back-btn">
            <a href="admin.php">Back to Admin Dashboard</a>
        </div>
    </div>
</body>
</html>
