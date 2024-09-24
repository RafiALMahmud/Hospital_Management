<?php
session_start();
$_SESSION;

$page = basename($_SERVER['PHP_SELF']); // Get current page name
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actions</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav>
        <ul>
            <li><a href="index.php" class="<?php if ($page == 'index.php') echo 'active'; ?>">Home</a></li>
            <li><a href="index.php/#about" class="<?php if ($page == 'index.php' && isset($_GET['about'])) echo 'active'; ?>">About</a></li>
            <li><a href="index.php#contact" class="<?php if ($page == 'index.php' && isset($_GET['contact'])) echo 'active'; ?>">Contact</a></li>
            <li><a href="experts.php" class="<?php if ($page == 'experts.php') echo 'active'; ?>">Experts</a></li>
        </ul>
    </nav>

    <!-- Actions Section -->
    <section id="actions">
        <h2>Actions</h2>

        <!-- Button 1: Admit -->
        <section class="action" id="admit">
            <h3>Admit</h3>
            <p>Click the button below to admit a new patient.</p>
            <button><a href="http://localhost/sax/admit_patient.php">Admit</a></button>
        </section>

        <!-- Button 2: Appointment List -->
        <section class="action" id="appointment-list">
            <h3>Appointment List</h3>
            <p>View the list of all scheduled appointments.</p>
            <button><a href="http://localhost/sax/appointments.php">Appointment List</a></button>
        </section>

        <!-- Button 3: Write a Review -->
        <section class="action" id="write-review">
            <h3>Write a Review</h3>
            <p>Fill your review about a counselor.</p>
            <button><a href="http://localhost/sax/reviews.php">Write a Review</a></button>
        </section>

        <!-- Button 4: My Bills -->
        <section class="action" id="my-bills">
            <h3>My Bills</h3>
            <p>View your billing details.</p>
            <button><a href="http://localhost/sax/bills.php">My Bills</a></button>
        </section>
    </section>

</body>
</html>

<style type="text/css">
/* General Styling */
body, html {
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    background-color: #f4f4f9;
}

/* Sticky Navigation Bar */
nav {
    background-color: #101419;
    color: #fff;
    text-align: center;
    padding: 12px 0;
    position: sticky;
    top: 0;
    z-index: 1000;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Navigation Menu Styling */
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
    padding: 10px;
    transition: color 0.3s ease;
}

/* Active Link Styling */
nav ul li a.active {
    border-bottom: 3px solid #1abc9c;
    color: #1abc9c;
}

/* Hover Effect */
nav ul li a:hover {
    color: #1abc9c;
}

/* Actions Section */
#actions {
    padding: 50px 0;
    text-align: center;
}

#actions h2 {
    font-size: 3rem;
    margin-bottom: 40px;
}

/* Action Box Styling */
.action {
    background-color: #ad8989;
    color: #333;
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 8px;
    transition: transform 0.45s ease;
}

.action h3 {
    font-size: 2rem;
    margin-bottom: 10px;
}

.action p {
    font-size: 1.5rem;
    margin-bottom: 20px;
}

.action button {
    background-color: #333;
    color: #fff;
    border: none;
    padding: 10px 20px;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.action button a {
    color: #fff;
    text-decoration: none;
}

.action button:hover {
    background-color: #555;
}

/* Hover Transform Effect */
.action:hover {
    transform: scale(1.16);
}
</style>
