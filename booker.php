<?php
session_start();
// Removed session check to allow access to all users without restrictions
?>

<!DOCTYPE html>
<html>
<head>
    <title>Appointment Booking</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <h1>Book an Appointment</h1>
        <form action="book_appointment.php" method="post">
            <label for="name">Your Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Your Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="phone">Your Phone Number:</label>
            <input type="text" id="phone" name="phone" required>

            <label for="name_of_the_doctor">Select Doctor:</label>
            <select id="name_of_the_doctor" name="name_of_the_doctor" required>
                <option value="">--Select Doctor--</option>
                <option value="Dr. Shamim Hasan">Dr. Shamim Hasan - Gastroliver</option>
                <option value="Dr. Imran Azad">Dr. Imran Azad - Cancer</option>
                <option value="Dr. Emily Rahman">Dr. Emily Rahman - Licensed Counselor</option>
                <option value="Dr. Mark Johnson">Dr. Mark Johnson - Neurologist</option>
                <!-- Add more counselors as needed -->
            </select>

            <label for="date">Preferred Date:</label>
            <input type="date" id="date" name="date" required>

            <label for="message">Additional Message:</label>
            <textarea id="message" name="message" rows="4"></textarea>

            <input type="submit" value="Book Appointment">
        </form>
    </div>

</body>
</html>

<style type="text/css">
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f5f2e6; /* Light brown background */
}

.container {
    max-width: 600px;
    margin: 50px auto;
    padding: 20px;
    background-color: #87CEEB; /* Sky blue background */
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    color: #333;
}

label {
    display: block;
    margin-bottom: 8px;
    color: #333;
}

input[type="text"],
input[type="email"],
input[type="date"],
select,
textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
}

input[type="submit"] {
    background-color: #8b4513; /* Brown color */
    color: #fff;
    border: none;
    border-radius: 5px;
    padding: 12px 20px;
    cursor: pointer;
    font-size: 16px;
}

input[type="submit"]:hover {
    background-color: #6b3e0e; /* Darker brown on hover */
}

textarea {
    resize: vertical;
    min-height: 100px;
}
</style>
