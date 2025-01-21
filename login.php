<?php 
session_start();
include("connection.php");
include("functions.php");

// Check if the user is already logged in
if (isset($_SESSION['user_ID'])) {
    // Redirect based on user type
    $query = "SELECT user_type FROM users WHERE ID = ? LIMIT 1";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $_SESSION['user_ID']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user_data = $result->fetch_assoc();
        if ($user_data['user_type'] === 'Admin') {
            header("Location: admin.php");
        } else if ($user_data['user_type'] === 'Patient') {
            header("Location: index.php");
        }
        die;
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Capture user input
    $user_name = $_POST['user_name'];
    $password = $_POST['password'];

    if (!empty($user_name) && !empty($password) && !is_numeric($user_name)) {
        // Use prepared statements to prevent SQL injection
        $query = "SELECT * FROM users WHERE user_name = ? LIMIT 1";
        $stmt = $con->prepare($query);
        $stmt->bind_param("s", $user_name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $user_data = $result->fetch_assoc();

            // Verify the hashed password
            if (password_verify($password, $user_data['password'])) {
                // Store user ID in session
                $_SESSION['user_ID'] = $user_data['ID']; 
                
                // Redirect based on user type
                if ($user_data['user_type'] === 'Admin') {
                    // Redirect to the admin login page
                    header("Location: admin_login.php");
                    exit;
                } else if ($user_data['user_type'] === 'Patient') {
                    header("Location: index.php");
                    exit;
                } else {
                    echo "<script>alert('Unknown user type.');</script>";
                }
                die;
            } else {
                echo "<script>alert('Incorrect password.');</script>";
            }
        } else {
            echo "<script>alert('No user found with that username.');</script>";
        }
    } else {
        echo "<script>alert('Please enter a valid username and password.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background: linear-gradient(135deg, #74c69d, #1d3557);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        #box {
            background-color: #ffffff;
            width: 350px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            border-radius: 12px;
            animation: slideIn 1s ease-out;
            text-align: center;
        }

        #box div {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #1d3557;
        }

        #text {
            height: 40px;
            border-radius: 8px;
            padding: 10px;
            border: solid 1px #dfe6e9;
            width: 100%;
            margin-bottom: 15px;
            font-size: 16px;
            box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s, border-color 0.3s;
        }

        #text:focus {
            border-color: #74c69d;
            box-shadow: 0 0 8px rgba(116, 198, 157, 0.5);
        }

        #button {
            padding: 12px;
            width: 100%;
            color: white;
            background: linear-gradient(90deg, #1d3557, #74c69d);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            transition: background 0.3s, transform 0.2s;
        }

        #button:hover {
            background: linear-gradient(90deg, #74c69d, #1d3557);
            transform: translateY(-3px);
        }

        #box a {
            color: #1d3557;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
        }

        #box a:hover {
            color: #74c69d;
            text-decoration: underline;
        }

        /* Animation for sliding effect */
        @keyframes slideIn {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <div id="box">
        <form method="post">
            <div>Login</div>
            <input id="text" type="text" name="user_name" placeholder="Username" required><br>
            <input id="text" type="password" name="password" placeholder="Password" required><br>
            <input id="button" type="submit" value="Login"><br><br>
            <a href="signup.php">Click to Signup</a>
        </form>
    </div>
</body>
</html>
