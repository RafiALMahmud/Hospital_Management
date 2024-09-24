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
        if ($user_type == 'Admin') {
            // Check if admin code is provided
            if (!empty($admin_code)) {
                // Insert into users table
                $query = "INSERT INTO users (user_name, password, user_type) VALUES ('$user_name', '$password', '$user_type')";
                mysqli_query($con, $query);
                header("Location: login.php");
                die;
            } else {
                echo "<script>alert('Failed to signup. No admin code given.');</script>";
            }
        } elseif ($user_type == 'Patient') {
            // If the user is a Patient, insert into the patients table
            if (!empty($age)) {
                // Insert into users table
                $query = "INSERT INTO users (user_name, password, user_type) VALUES ('$user_name', '$password', '$user_type')";
                mysqli_query($con, $query);
                $user_id = mysqli_insert_id($con); // Get the auto-incremented user ID
                
                // Insert into patients table (MySQL will auto-generate patient_id)
                $query_patient = "INSERT INTO patients (name, age, user_id) VALUES ('$user_name', '$age', '$user_id')";
                mysqli_query($con, $query_patient);
                
                header("Location: login.php");
                die;
            } else {
                echo "Please enter the age for patient signup.";
            }
        }
    } else {
        echo "Please enter some valid information!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Signup</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div id="box">
        <form method="post">
            <div class="title">Signup</div>

            <input id="text" type="text" name="user_name" placeholder="Username" required><br><br>
            <input id="text" type="password" name="password" placeholder="Password" required><br><br>

            <label for="user_type" class="label">Select User Type:</label>
            <select id="text" name="user_type" required onchange="showAdminCode(this.value)">
                <option value="">--Select User Type--</option>
                <option value="Patient" <?php if ($user_type == 'Patient') echo 'selected'; ?>>Patient</option>
                <option value="Admin" <?php if ($user_type == 'Admin') echo 'selected'; ?>>Admin</option>
            </select><br><br>

            <!-- Conditionally show the age input if user selects 'Patient' -->
            <?php if ($user_type == 'Patient'): ?>
                <label for="age" class="label">Age:</label>
                <input id="text" type="number" name="age" placeholder="Age" value="<?php echo htmlspecialchars($age); ?>" required><br><br>
            <?php endif; ?>

            <!-- Admin Code Input -->
            <div id="adminCode" style="display:none;">
                <label for="admin_code" class="label">Admin Code:</label>
                <input id="text" type="text" name="admin_code" placeholder="Enter Admin Code"><br><br>
            </div>

            <input id="button" type="submit" value="Signup"><br><br>

            <a href="login.php">Click to Login</a><br><br>
        </form>
    </div>

    <script>
        function showAdminCode(userType) {
            var adminCodeDiv = document.getElementById('adminCode');
            if (userType === 'Admin') {
                adminCodeDiv.style.display = 'block';
            } else {
                adminCodeDiv.style.display = 'none';
            }
        }
    </script>

</body>
</html>
<style type="text/css">
body {
    margin: 0;
    padding: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f4f4f9;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

/* Signup Box */
#box {
    background-color: #2c3e50;
    width: 350px;
    padding: 40px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    color: white;
}

/* Title Style */
.title {
    font-size: 24px;
    margin-bottom: 20px;
    text-align: center;
    color: #ecf0f1;
    font-weight: bold;
}

/* Input Fields */
#text {
    width: 100%;
    height: 35px;
    padding: 8px;
    margin: 8px 0;
    border: none;
    border-radius: 5px;
    font-size: 14px;
    color: #2c3e50;
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
}

#text:focus {
    outline: none;
    box-shadow: 0 0 6px #3498db;
}

/* Button Styles */
#button {
    width: 100%;
    padding: 10px;
    background-color: #3498db;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

#button:hover {
    background-color: #2980b9;
}

/* Label Styles */
.label {
    font-size: 14px;
    color: #ecf0f1;
    margin-bottom: 5px;
}

/* Link Styles */
.link {
    display: block;
    text-align: center;
    margin-top: 20px;
    color: #ecf0f1;
    text-decoration: none;
    font-size: 14px;
}

.link:hover {
    text-decoration: underline;
}

/* Responsive Design */
@media only screen and (max-width: 400px) {
    #box {
        width: 90%;
    }

    #text, #button {
        font-size: 16px;
    }
}
</style>
