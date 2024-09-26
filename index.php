<?php
session_start();
include("connection.php"); 

// Fetching reviews from the database
$query = "SELECT name_of_the_counselor, review FROM reviews";
$result = mysqli_query($con, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mental Health Directory</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Navigation Bar -->
<nav>
    <ul>
        <li><a href="#home">Home</a></li>
        <li><a href="#about">About</a></li>
        <li><a href="#contact">Contact</a></li>
        <li><a href="http://localhost/sax/experts.php">Experts</a></li>
        <li><a href="http://localhost/sax/appointments.php">Appointment</a></li>
        <li><a href="http://localhost/sax/logout.php">Logout</a></li>
    </ul>
</nav>

<!-- Home Section -->
<section id="home">
    <div class="content">
        <h1>Memorial - a premier healthcare facility dedicated to providing medical care to our community</h1>
        <p>Welcome to Memorial, your comprehensive resource for connecting with top-tier doctors. 'Putting Patients First' is not just our motto; it is our way of life. Our journey began with a vision to deliver compassionate and high-quality services to our patients and visitors.</p>

        <div class="buttons">
            <button><a href="http://localhost/sax/experts.php">Find Experts</a></button>
            <button><a href="http://localhost/sax/actions.php">Patient</a></button> <!-- New Patient Button -->
        </div>
    </div>
</section>

<!-- About Section -->
<section id="about">
    <h2>About Us</h2>
    <p>At Memorial Hospital, we are dedicated to providing exceptional healthcare services to our community. Our facility is equipped with advanced technology and staffed by a team of compassionate and skilled medical professionals committed to delivering the highest quality of care to every patient.</p>

    <h3>What We Offer</h3>
    <p><b>Comprehensive Services:</b> Our hospital offers a wide range of medical services, including emergency care, surgical procedures, outpatient services, and specialized treatment programs, ensuring that we meet the diverse healthcare needs of our patients.</p>
    <p><b>Patient Testimonials:</b> Hear from our patients about their experiences at Memorial Hospital, helping you make informed decisions about your healthcare journey.</p>

    <!-- Add the reviews section here -->
    <h3>Patient Reviews</h3>
    <div class="reviews-container">
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='review'>";
                echo "<h4>Counselor: " . htmlspecialchars($row['name_of_the_counselor']) . "</h4>";
                echo "<p>" . htmlspecialchars($row['review']) . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>No reviews available at this time.</p>";
        }
        ?>
    </div>
</section>

<!-- Contact Section -->
<section id="contact">
    <h2>Contact Us</h2>
    <p>Email: rafi@email.com</p>
    <p>Phone: +8801974624144</p>
</section>

</body>
</html>


<?php

mysqli_close($con);
?>



<!-- Stylesheet (CSS) -->
<style type='text/css'>
    body, html {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
    }

    nav {
        background-color: #101419;
        color: #fff;
        text-align: center;
        padding: 12px 0;
    }

    nav ul {
        list-style: none;
        padding: 0;
    }

    nav ul li {
        display: inline-block;
        margin-right: 20px;
    }

    nav ul li a {
        text-decoration: none;
        color: #fff;
        font-weight: bold;
    }

    #home {
        background-color: #eed7c5;
        color: #333;
        text-align: center;
        padding: 160px 0;
    }

    #home .content {
        max-width: 1000px;
        margin: auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    #home .intro {
        flex: 2;
        padding-right: 30px;
    }

    #home img {
        max-width: 100%;
        max-height: 350px;
        height: auto;
        border-radius: 50%;
    }

    #home .buttons {
        margin-top: 20px;
    }

    #home .buttons button {
        background-color: #333;
        color: #fff;
        border: none;
        padding: 15px 25px;
        font-size: 1rem;
        cursor: pointer;
        transition: background-color 0.3s ease;
        margin-right: 20px; /* Space between buttons */
    }

    #home .buttons button a {
        color: #fff;
        text-decoration: none;
    }

    #home .buttons button:hover {
        background-color: #555;
    }

    #about {
        background-color: #b1a7a6;
        color: #333;
        text-align: center;
        padding: 150px 0;
    }

    #about h2 {
        font-size: 2.0rem;
        margin-bottom: 30px;
    }

    #about h3 {
        font-size: 1.2rem;
        margin-bottom: 30px;
    }

    #about p {
        font-size: 1.0rem;
        margin-bottom: 30px;
    }

    #contact {
        background-color: #161a1d;
        color: #fff;
        text-align: center;
        padding: 50px 0;
    }

    #contact h2 {
        font-size: 1.6rem;
        margin-bottom: 20px;
    }

    #contact p {
        font-size: 1.1rem;
        margin-bottom: 15px;
    }

    #contact a {
        color: #fff;
        text-decoration: none;
        font-weight: bold;
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

    #home img {
        max-width: 100%;
        max-height: 400px;
        height: auto;
        border-radius: 50%;
        margin-bottom: 20px;
    }

    #experts {
        background-color: #ad8989;
        color: #333;
        text-align: center;
        padding: 120px 0;
    }

    #experts h2 {
        font-size: 3rem;
        margin-bottom: 40px;
    }

    .expert {
        margin-bottom: 50px;
    }

    .expert h3 {
        font-size: 2rem;
        margin-bottom: 20px;
    }

    .expert p {
        font-size: 1.5rem;
        margin-bottom: 40px;
    }

    @media screen and (max-width: 768px) {
        .content {
            flex-direction: column;
        }

        #home img {
            max-height: 250px;
        }
    }
</style>
