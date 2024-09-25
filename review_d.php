<?php
include("connection.php"); // Database connection

// Fetching reviews from the database
$query = "SELECT review_id, name_of_the_counselor, review FROM reviews";
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
                
                <!-- Delete Button -->
                <form action="delete_review.php" method="POST" style="display: inline-block;">
                    <input type="hidden" name="review_id" value="<?php echo $row['review_id']; ?>">
                    <button type="submit" class="delete-button" onclick="return confirm('Are you sure you want to delete this review?');">Delete</button>
                </form>
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

.delete-button {
    background-color: #e74c3c;
    color: #fff;
    border: none;
    padding: 5px 10px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 0.9rem;
}

.delete-button:hover {
    background-color: #c0392b;
}

</style>
