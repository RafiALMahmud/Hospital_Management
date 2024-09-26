<?php 
session_start();
include("connection.php");
include("functions.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Something was posted
    $user_name = $_POST['user_name'];
    $password = $_POST['password'];

    if (!empty($user_name) && !empty($password) && !is_numeric($user_name)) {
        // Read from database
        $query = "SELECT * FROM users WHERE user_name = '$user_name' LIMIT 1";
        $result = mysqli_query($con, $query);

        if ($result) {
            if ($result && mysqli_num_rows($result) > 0) {
                $user_data = mysqli_fetch_assoc($result);
                
                if ($user_data['password'] === $password) {
                    $_SESSION['user_ID'] = $user_data['ID']; 
                    echo "User ID set in session: " . $_SESSION['user_ID']; 

                    if ($user_data['user_type'] === 'Admin') {
                        header("Location: admin.php"); 
                    } else if ($user_data['user_type'] === 'Patient') {
                        header("Location: index.php"); 
                    } else {
                        echo "Unknown user type.";
                    }
                    die;
                } else {
                    echo "Incorrect password.";
                }
            } else {
                echo "User not found.";
            }
        } else {
            echo "Query failed.";
        }
    } else {
        echo "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style type="text/css">
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background-color: #f0f2f5;
        }

        #box {
            background-color: #fff;
            margin: auto;
            width: 300px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-top: 100px;
        }

        #text {
            height: 25px;
            border-radius: 5px;
            padding: 4px;
            border: solid thin #aaa;
            width: 100%;
            margin-bottom: 10px;
        }

        #button {
            padding: 10px;
            width: 100%;
            color: white;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        #button:hover {
            background-color: #0056b3;
        }

        #box a {
            color: #007bff;
            text-decoration: none;
        }

        #box a:hover {
            text-decoration: underline;
        }

        #box div {
            font-size: 20px;
            margin: 10px;
            color: #333;
            text-align: center;
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

            <a href="signup.php">Click to Signup</a><br><br>
        </form>
    </div>
</body>
</html>
