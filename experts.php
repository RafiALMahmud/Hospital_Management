<?php
session_start();
$_SESSION;
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

        <!-- Expert 1 -->
        <section class="expert" id="expert1">
            <h3>Dr. Shamim Hasan</h3>
            <p>Gastroliver</p>
            <p>Specializes in Gastroenterology & Liver diseases.</p>
            <button><a href="http://localhost/sax/booker.php">Book an appointment</a></button>
        </section>

        <!-- Expert 2 -->
        <section class="expert" id="expert2">
            <h3>Dr. Imran Azad</h3>
            <p>Cancer</p>
            <p>Focuses on Cancer curing.</p>
            <button><a href="/sax/booker.php">Book an appointment</a></button>
        </section>

        <!-- Expert 3 -->
        <section class="expert" id="expert3">
            <h3>Dr. Emily Rahman</h3>
            <p>Licensed Counselor</p>
            <p>Provides individual and group counseling for stress management.</p>
            <button><a href="/sax/booker.php">Book an appointment</a></button>
        </section>

        <!-- Expert 4 -->
        <section class="expert" id="expert4">
            <h3>Dr. Mark Johnson</h3>
            <p>Neurologist</p>
            <p>Specialized brain surgeon focusing on neurological disorders.</p>
            <button><a href="/sax/booker.php">Book an appointment</a></button>
        </section>
    </section>
</body>
</html>
