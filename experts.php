<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login_db"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from doctors table
$sql = "SELECT name, speciality, address FROM doctors";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Experts</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* General Styling */
        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        /* Navigation Bar */
        nav {
            background-color: #101419;
            color: #fff;
            text-align: center;
            padding: 12px 0;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 999;
        }

        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        nav ul li {
            display: inline-block;
            margin-right: 20px;
        }

        nav ul li a {
            text-decoration: none;
            color: #fff;
            font-weight: bold;
            padding: 10px 15px;
            transition: color 0.3s ease;
        }

        nav ul li a:hover {
            color: #ddd;
        }

        /* Active Link Styling */
        nav ul li a.active {
            background-color: #333;
            border-radius: 4px;
            padding: 10px 15px;
        }

        /* Experts Section */
        #experts {
            padding: 100px 0 50px; /* Adjusted padding to avoid overlap with fixed nav */
            text-align: center;
        }

        #experts h2 {
            font-size: 3rem;
            margin-bottom: 40px;
        }

        .expert {
            background-color: #ad8989;
            color: #333;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            transition: transform 0.3s ease;
        }

        .expert h3 {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .expert p {
            font-size: 1.5rem;
            margin-bottom: 20px;
        }

        .expert button {
            background-color: #333;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .expert button a {
            color: #fff;
            text-decoration: none;
        }

        .expert button:hover {
            background-color: #555;
        }

        button:hover,
        a:hover {
            background-color: #555;
            color: #fff;
            transition: background-color 0.45s ease, color 0.45s ease;
        }

        section {
            transition: transform 0.45s ease;
        }

        section:hover {
            transform: scale(1.16);
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="index.php/#about">About</a></li>
            <li><a href="index.php#contact">Contact</a></li>
            <li><a class="active" href="experts.php">Experts</a></li>
        </ul>
    </nav>

    <!-- Experts Section -->
    <section id="experts">
        <h2>Our Experts</h2>

        <?php
        if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                echo '<section class="expert">';
                echo '<h3>' . $row["name"] . '</h3>';
                echo '<p>' . $row["speciality"] . '</p>';
                echo '<p>' . $row["address"] . '</p>';
                echo '<button><a href="/sax/booker.php">Book an appointment</a></button>';
                echo '</section>';
            }
        } else {
            echo "0 results";
        }
        $conn->close();
        ?>
    </section>
</body>
</html>
