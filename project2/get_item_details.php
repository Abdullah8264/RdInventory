<?php
// Require database file
require_once('database.php');


// Retrieve item details based on the received item_id
$itemId = $_POST['item_id'];

// Prepare the SQL statement
$query = "SELECT quantity, price FROM materials WHERE id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $itemId);

// Execute the prepared statement
$stmt->execute();

// Bind the result variables
$stmt->bind_result($quantity, $price);

// Fetch the result
$stmt->fetch();

// Close the prepared statement
$stmt->close();

// Create an array with the item details
$itemDetails = array(
  'quantity' => $quantity,
  'price' => $price
);

// Return the item details as JSON
echo json_encode($itemDetails);
?>
