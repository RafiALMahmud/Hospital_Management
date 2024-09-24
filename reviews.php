<?php
session_start();

// Include database connection
include("connection.php");

// Initialize variables for storing feedback and errors
$review = "";
$review_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate review
    if (empty(trim($_POST["review"]))) {
        $review_err = "Please enter your review.";
    } else {
        $review = trim($_POST["review"]);
    }

    // Check for input errors before inserting into the database
    if (empty($review_err)) {
        // Prepare an insert statement
        // Remove ID from the INSERT statement since it's auto-increment
        $query = "INSERT INTO reviews (name_of_the_counselor, review) VALUES (?, ?)";

        if ($stmt = mysqli_prepare($con, $query)) {
            // Bind variables to the prepared statement as parameters
            // Note: ID is not included in the binding
            mysqli_stmt_bind_param($stmt, "ss", $_POST['name_of_the_counselor'], $review);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to the reviews page after successful submission
                header("Location: reviews.php");
                exit;
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($con);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Review</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        select,
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .text-danger {
            color: red;
        }

        .btn {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Submit Review</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Select Counselor</label>
                <select name="name_of_the_counselor">
                    <option value="Dr. Shamim Hasan - Gastroliver">Dr. Shamim Hasan - Gastroliver</option>
                    <option value="Dr. Imran Azad - Cancer">Dr. Imran Azad - Cancer</option>
                    <option value="Dr. Emily Rahman - Licensed Counselor">Dr. Emily Rahman - Licensed Counselor</option>
                    <option value="Dr. Mark Johnson - Neurologist">Dr. Mark Johnson - Neurologist</option>
                </select>
            </div>
            <div class="form-group">
                <label>Review</label>
                <textarea name="review" rows="5"><?php echo htmlspecialchars($review); ?></textarea>
                <span class="text-danger"><?php echo $review_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn" value="Submit">
            </div>
        </form>
    </div>
</body>
</html>
