<?php
  // Reqire database file
  require_once('database.php');

$item_id = $_POST['item_id'];
$quantity = $_POST['quantity'];
$price = $_POST['price'];

$query = "UPDATE materials SET quantity='$quantity', price='$price' WHERE id='$item_id'";
$result = mysqli_query($mysqli, $query);

if ($result) {
  echo "Item updated successfully.";
} else {
  echo "Error updating item: " . mysqli_error($mysqli);
}

mysqli_close($mysqli);
?>
