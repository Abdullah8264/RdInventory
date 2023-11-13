<?php
session_start();

require_once('database.php'); // Include the database connection file

if (isset($_SESSION["user_id"])) {
    // The user is logged in, do something here
    $user_id = $_SESSION["user_id"];
    // Add any other session variables you need here
} else {
    // The user is not logged in, redirect to the login form
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
<link rel="stylesheet" type="text/css" href="navbar.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">


 <title>Update Item</title>
  <link rel="icon" href="/icon image/RDGRP.ico" type="image/x-icon">

<style>
  body {
			font-family: Arial, sans-serif;
		}
 .line {
  width: 50%;
  margin-left: 0;
  color: white;
}
.button-group {
  display: flex;
  justify-content:space-between;
  align-items: center;
  margin-top: 10px;
}

.button-group input[type="submit"],
.button-group input[type="button"] {
  flex: 10 10 auto;
  margin-left: 10px;
  margin-right:100px;
  
}
.logout-button {
  position: absolute;
        top: 25px; /* Adjust the top and right values to position the button */
        right: 50px;
        text-decoration: none;
        color: white; /* Change the color as needed */
    }
</style>

 
</head>
<body>
<a href="logout.php" class="logout-button">
    <i class="fas fa-sign-in-alt fa-flip-horizontal"></i> Logout
    </a>
<nav>
  <a href="./start.php">Add Item</a>
  <a href="./issue_item.php">Issue Item</a>
  <a class="active" href="update.php">Update Item</a>
  <a href="./inventory.php">Inventory</a>
  <a href="./calc.php">Income and Expenses</a>
  <a href="./transaction.php">Transactions</a>


</nav>

<h1>UPDATE ITEM</h1>
<hr class="line">
<br>
<form id="update-form" action="" method="post">
  <label>Select Item:</label>
  <select name="item_id" id="item-select" required>
    <option value="">Select an item</option>
    <!-- PHP code to retrieve items from database -->
    <?php
      // Require database file
      require_once('database.php');

      $query = "SELECT * FROM materials";
      $result = mysqli_query($mysqli, $query);
      while ($row = mysqli_fetch_assoc($result)) {
        echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
      }
    ?>
  </select>
  <br>
  <label>Existing Quantity:</label>
  <label id="quantity-label"></label>
  <br>
  <label>Existing Price:</label>
  <label id="price-label"></label>
  <br>
  <label>New Quantity:</label>
  <input type="number" name="quantity" required>
  <br>
  <label>New Price:</label>
  <input type="number" name="price" required>
  <br>
  <div class="button-group">
    <input type="submit" value="Update">
    <input type="button" value="Clear" onclick="resetForm()">
    <input type="button" value="Remove" onclick="removeItem()">
  </div>
</form>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function() {
    $("#item-select").on("change", function() {
      var itemId = $(this).val();
      if (itemId !== "") {
        $.ajax({
          url: "get_item_details.php", // Replace with the PHP script to retrieve item details
          type: "post",
          data: { item_id: itemId },
          success: function(response) {
            var itemDetails = JSON.parse(response);
            $("#quantity-label").text(itemDetails.quantity);
            $("#price-label").text(itemDetails.price);
          }
        });
      } else {
        $("#quantity-label").text("");
        $("#price-label").text("");
      }
    });

    $("#update-form").on("submit", function(e) {
      e.preventDefault();
      $.ajax({
        url: "update_item.php",
        type: "post",
        data: $("#update-form").serialize(),
        success: function(response) {
          $("#update-form")[0].reset();
          alert(response);
          $("#quantity-label").text("");
          $("#price-label").text("");
        }
      });
    });
  });

  function resetForm() {
    $("#update-form")[0].reset();
    $("#quantity-label").text("");
    $("#price-label").text("");
  }
  
  function removeItem() {
    var itemId = $("#item-select").val();
    if (itemId !== "") {
      if (confirm("Are you sure you want to remove this item?")) {
        $.ajax({
          url: "remove_item.php",
          type: "post",
          data: { item_id: itemId },
          success: function(response) {
            alert(response);
            // Optionally, you can reload the page or perform any other necessary action
          }
        });
      }
    }
  }
</script>
</body>
</html>