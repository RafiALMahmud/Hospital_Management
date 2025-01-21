<?php 
session_start();
include("connection.php");
include("functions.php");

// Check if the user is already logged in
if (!isset($_SESSION['user_ID'])) {
    header("Location: login.php");
    die;
}

// Check if the admin is already logged in
if (isset($_SESSION['admin_ID'])) {
    header("Location: admin.php");
    die;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Capture admin input
    $admin_name = $_POST['admin_name'];
    $password = $_POST['password'];

    if (!empty($admin_name) && !empty($password)) {
        // Use prepared statements to prevent SQL injection
        $query = "SELECT * FROM admin WHERE name = ? LIMIT 1";
        $stmt = $con->prepare($query);
        $stmt->bind_param("s", $admin_name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $admin_data = $result->fetch_assoc();

            // Verify the hashed password
            if (hash('sha256', $password) === $admin_data['password']) {
                // Destroy any existing session
                session_unset();
                session_destroy();
                session_start();

                // Store admin ID in session
                $_SESSION['admin_ID'] = $admin_data['admin_id']; 
                
                // Redirect to the admin page
                header("Location: admin.php");
                exit;
            } else {
                echo "<script>alert('Incorrect password.');</script>";
            }
        } else {
            echo "<script>alert('No admin found with that name.');</script>";
        }
    } else {
        echo "<script>alert('Please enter a valid name and password.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background: linear-gradient(135deg, #ff7e5f, #feb47b); /* Updated background gradient */
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
            color: #333; /* Updated text color */
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
            border-color: #ff7e5f; /* Updated focus border color */
            box-shadow: 0 0 8px rgba(255, 126, 95, 0.5); /* Updated focus box shadow */
        }

        #button {
            padding: 12px;
            width: 100%;
            color: white;
            background: linear-gradient(90deg, #ff7e5f, #feb47b); /* Updated button gradient */
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            transition: background 0.3s, transform 0.2s;
        }

        #button:hover {
            background: linear-gradient(90deg, #feb47b, #ff7e5f); /* Updated hover gradient */
            transform: translateY(-3px);
        }

        #box a {
            color: #333; /* Updated link color */
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
        }

        #box a:hover {
            color: #ff7e5f; /* Updated hover link color */
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
            <div>Admin Login</div>
            <input id="text" type="text" name="admin_name" placeholder="Admin Name" required><br>
            <input id="text" type="password" name="password" placeholder="Password" required><br>
            <input id="button" type="submit" value="Login"><br><br>
        </form>
    </div>
</body>
</html>