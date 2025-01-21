<?php 
session_start();
include("connection.php");
include("functions.php");

$user_type = ''; 
$age = ''; 
$admin_code = ''; 

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Capture form data
    $user_name = $_POST['user_name'];
    $password = $_POST['password'];
    $user_type = $_POST['user_type']; // Get the selected user type
    $age = isset($_POST['age']) ? $_POST['age'] : null; // Capture age if patient
    $admin_code = isset($_POST['admin_code']) ? $_POST['admin_code'] : ''; // Capture admin code

    if (!empty($user_name) && !empty($password) && !is_numeric($user_name)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        if ($user_type == 'Admin') {
            // Check if admin code is provided
            if (!empty($admin_code)) {
                // Insert into users table using prepared statements
                $query = $con->prepare("INSERT INTO users (user_name, password, user_type) VALUES (?, ?, ?)");
                $query->bind_param("sss", $user_name, $hashed_password, $user_type);
                $query->execute();
                header("Location: login.php");
                die;
            } else {
                echo "<script>alert('Failed to signup. No admin code given.');</script>";
            }
        } elseif ($user_type == 'Patient') {
            // Validate age
            if (!empty($age) && is_numeric($age) && $age > 0 && $age <= 200) {
                // Insert into users table using prepared statements
                $query = $con->prepare("INSERT INTO users (user_name, password, user_type) VALUES (?, ?, ?)");
                $query->bind_param("sss", $user_name, $hashed_password, $user_type);
                $query->execute();
                $user_id = $con->insert_id; // Get the auto-incremented user ID

                // Insert into patients table using prepared statements
                $query = $con->prepare("INSERT INTO patients (user_id, name, age) VALUES (?, ?, ?)");
                $query->bind_param("isi", $user_id, $user_name, $age);
                $query->execute();

                header("Location: login.php");
                die;
            } else {
                echo "<script>alert('Failed to signup. Age must be between 1 and 200.');</script>";
            }
        }
    } else {
        echo "<script>alert('Please enter valid username and password.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form method="post" id="signup-form">
        <div class="title">Signup</div>
        <div>
            <label for="user_name" class="label">Username:</label>
            <input type="text" id="user_name" name="user_name" class="input-box" required>
        </div>
        <div>
            <label for="password" class="label">Password:</label>
            <input type="password" id="password" name="password" class="input-box" required>
        </div>
        <div>
            <label for="user_type" class="label">User Type:</label>
            <select id="user_type" name="user_type" class="input-box" required onchange="showFields(this.value)">
                <option value="">Select User Type</option>
                <option value="Patient">Patient</option>
                <option value="Admin">Admin</option>
            </select>
        </div>
        <div id="age_field" style="display: none;">
            <label for="age" class="label">Age:</label>
            <input type="number" id="age" name="age" class="input-box">
        </div>
        <div id="admin_code_field" style="display: none;">
            <label for="admin_code" class="label">Admin Code:</label>
            <input type="text" id="admin_code" name="admin_code" class="input-box">
        </div>
        <div>
            <button type="submit" id="button">Signup</button>
        </div>
    </form>

    <script>
        function showFields(userType) {
            var ageField = document.getElementById('age_field');
            var adminCodeField = document.getElementById('admin_code_field');
            if (userType === 'Patient') {
                ageField.style.display = 'block';
                adminCodeField.style.display = 'none';
            } else if (userType === 'Admin') {
                ageField.style.display = 'none';
                adminCodeField.style.display = 'block';
            } else {
                ageField.style.display = 'none';
                adminCodeField.style.display = 'none';
            }
        }

        // Set initial state based on the selected user type
        document.addEventListener('DOMContentLoaded', function() {
            var userType = document.getElementById('user_type').value;
            showFields(userType);
        });
    </script>
</body>
</html>

<style type="text/css">
body {
    margin: 0;
    padding: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #e3f2fd;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

#signup-form {
    background-color: #ffffff;
    width: 400px;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

.title {
    font-size: 28px;
    margin-bottom: 20px;
    text-align: center;
    color: #1976d2;
    font-weight: bold;
}

.label {
    font-size: 14px;
    color: #333333;
    margin-bottom: 5px;
}

.input-box {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #1976d2;
    border-radius: 5px;
    font-size: 14px;
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
}

.input-box:focus {
    outline: none;
    border-color: #1e88e5;
    box-shadow: 0 0 6px #1e88e5;
}

#button {
    width: 100%;
    padding: 12px;
    background-color: #1976d2;
    color: #ffffff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

#button:hover {
    background-color: #1565c0;
}

@media only screen and (max-width: 400px) {
    #signup-form {
        width: 90%;
    }
}
</style>
