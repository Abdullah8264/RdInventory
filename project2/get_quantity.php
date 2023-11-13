<?php
// Require database file
require_once('database.php');

if (isset($_POST['name'])) {
  $itemName = $_POST['name'];

  // Fetch the quantity for the selected item from the database
  $query = "SELECT quantity FROM materials WHERE name = '$itemName'";
  $result = mysqli_query($mysqli, $query);

  if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $quantity = $row['quantity'];

    // Return the quantity as the response
    echo $quantity;
  } else {
    echo "Quantity not found.";
  }
} else {
  echo "Invalid request.";
}

// Close database connection
mysqli_close($mysqli);
?>


