<?php
// delete_category.php

// Assuming you have already set up the database connection
require_once('database.php');

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    if (isset($_GET["category"])) {
        $category = $_GET["category"];

        // Delete the selected category from the database
        $query = "DELETE FROM category WHERE category = '$category'";
        $result = mysqli_query($mysqli, $query);

        if ($result) {
            echo "Category deleted successfully!";
        } else {
            echo "Failed to delete the category. Please try again.";
        }
    } else {
        echo "Category not specified.";
    }
}
?>
