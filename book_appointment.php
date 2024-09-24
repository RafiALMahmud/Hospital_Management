<?php
include("connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $date = trim($_POST["date"]);
    $message = trim($_POST["message"]);
    
    // Correctly get the selected doctor
    $name_of_the_doctor = isset($_POST["name_of_the_doctor"]) ? trim($_POST["name_of_the_doctor"]) : '';

    // Validate form data
    $errors = [];
    if (empty($name)) {
        $errors[] = "Name is required.";
    }
    if (empty($email)) {
        $errors[] = "Email is required.";
    }
    if (empty($phone)) {
        $errors[] = "Phone is required.";
    }
    if (empty($date)) {
        $errors[] = "Date is required.";
    }
    if (empty($name_of_the_doctor)) {
        $errors[] = "Counselor selection is required.";
    }

    // If there are errors, display them
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<div class='error-message'>$error</div>";
        }
    } else {
        // Check if the patient exists in the patients table
        $query_patient = "SELECT patient_id FROM patients WHERE name = '$name'";
        $result_patient = mysqli_query($con, $query_patient);

        if (mysqli_num_rows($result_patient) > 0) {
            // If the patient exists, retrieve the patient_id
            $patient_row = mysqli_fetch_assoc($result_patient);
            $patient_id = $patient_row['patient_id'];

            // Save the appointment details to the appointments table
            $query_appointment = "INSERT INTO appointments (counselor_name, patient_id, patient_name, appointment_date, message) VALUES ('$name_of_the_doctor', '$patient_id', '$name', '$date', '$message')";

            if (mysqli_query($con, $query_appointment)) {
                echo "<div class='success-message'>";
                echo "<h2>Appointment booked successfully!</h2>";
                echo "<p>Name: $name</p>";
                echo "<p>Email: $email</p>";
                echo "<p>Phone: $phone</p>";
                echo "<p>Date: $date</p>";
                echo "<p>Message: $message</p>";
                echo "<p>Appointment Fee: 1000</p>"; // Added fee message
                echo "<button onclick=\"window.location.href='experts.php'\">Book Another Appointment</button>";
                echo "</div>";
            } else {
                echo "<div class='error-message'>Error: " . mysqli_error($con) . "</div>";
            }
        } else {
            echo "<div class='error-message'>Patient not found. Please register the patient first.</div>";
        }
    }
} else {
    // If the form is not submitted, redirect back to the appointment form page
    header("Location: appointment_form.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Confirmation</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to external CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f2e6; /* Light brown background */
            margin: 0;
            padding: 20px;
        }

        .success-message {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #d0f0c0; /* Light greenish background */
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center; /* Center the text */
        }

        .success-message h2 {
            font-size: 24px; /* Larger font size for the heading */
            color: #333;
        }

        .success-message p {
            font-size: 18px; /* Larger font size for the paragraph text */
            color: #333;
        }

        .error-message {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f8d7da; /* Light red background */
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            color: #721c24; /* Dark red text */
            text-align: center;
        }

        button {
            background-color: #8b4513; /* Brown color */
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 12px 20px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px; /* Spacing above the button */
        }

        button:hover {
            background-color: #6b3e0e; /* Darker brown on hover */
        }
    </style>
</head>
<body>
    <!-- The success and error messages will be displayed here -->
</body>
</html>
