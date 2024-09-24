<?php
include("connection.php"); // Database connection

// Fetching reviews from the database
$query = "SELECT name_of_the_counselor, review FROM reviews";
$result = mysqli_query($con, $query);

?>

<!-- Reviews Display -->
<div class="reviews-wrapper">
    <?php
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <div class="review-card">
                <h4 class="counselor-name"><?php echo htmlspecialchars($row['name_of_the_counselor']); ?></h4>
                <p class="review-text">"<?php echo htmlspecialchars($row['review']); ?>"</p>
            </div>
            <?php
        }
    } else {
        echo "<p>No reviews available at this time.</p>";
    }

    // Close the database connection
    mysqli_close($con);
    ?>
</div>
<style type="text/css">
/* Reviews Styling */
.reviews-wrapper {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f4f4f4;
    border-radius: 10px;
}

.review-card {
    background-color: #ffffff;
    border: 1px solid #ccc;
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.counselor-name {
    font-size: 1.3rem;
    font-weight: bold;
    color: #2c3e50;
    margin-bottom: 10px;
}

.review-text {
    font-size: 1rem;
    font-style: italic;
    color: #555;
}

.reviews-wrapper p {
    text-align: center;
    font-size: 1.2rem;
    color: #999;
}
