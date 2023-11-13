<?php
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Require database file
    require_once('database.php');

    // Retrieve the form data
    $name = $_POST["name"];
    $quantity = $_POST["quantity"];
    $category = $_POST["category"];
    $price = $_POST["price"];

    // Insert the form data into the materials table with the timestamp
    $sql = "INSERT INTO materials (name, quantity, category, price, timestamp_column) 
            VALUES ('$name', '$quantity', '$category', '$price', CURRENT_TIMESTAMP)";
    if ($mysqli->query($sql) === TRUE) {
        echo "Successfully inserted with timestamp: " . date('Y-m-d H:i:s');
    } else {
        echo "Error: " . $sql . "<br>" . $mysqli->error;
    }

    // Close the MySQL connection
    $mysqli->close();

    // Stop the script from running further
    exit();
}
?>
