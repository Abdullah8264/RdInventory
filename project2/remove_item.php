<?php
session_start();
if(isset($_SESSION["user_id"])){
    // The user is logged in, do something here
    $user_id = $_SESSION["user_id"];
    // Add any other session variables you need here
} else {
    // The user is not logged in, redirect to login form
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the item_id parameter is provided
    if (isset($_POST["item_id"])) {
        // Retrieve the item_id from the request
        $item_id = $_POST["item_id"];

        // Require database file
        require_once('database.php');

        // Prepare and execute the SQL query to remove the item from the database
        $query = "DELETE FROM materials WHERE id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("i", $item_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "Item removed successfully.";
        } else {
            echo "Failed to remove item.";
        }

        $stmt->close();
        $mysqli->close();
    } else {
        echo "Missing item_id parameter.";
    }
} else {
    echo "Invalid request.";
}
?>
