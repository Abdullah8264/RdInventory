<?php
// save_category.php

// Assuming you have already set up the database connection
require_once('database.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $category = $_POST["category"];

    // Insert the new category into the database
    $query = "INSERT INTO category (category) VALUES ('$category')";
    $result = mysqli_query($mysqli, $query);

    if ($result) {
        echo "Category added successfully!";
    } else {
        echo "Failed to add the category. Please try again.";
    }
}
?>
