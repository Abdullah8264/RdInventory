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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" type="text/css" href="navbar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">




    <title>Issue Item</title>
    <link rel="icon" href="/icon image/RDGRP.ico" type="image/x-icon">
    
    <style>
    /* CSS styles for the form */
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
  <title>Issue Item</title>
</head>

<body>
<a href="logout.php" class="logout-button">
    <i class="fas fa-sign-in-alt fa-flip-horizontal"></i> Logout
    </a>
<nav>
  <a href="./start.php">Add Item</a>
  <a class="active" href="./issue_item.php">Issue Item</a>
  <a href="update.php">Update Item</a>
  <a href="./inventory.php">Inventory</a>
  <a href="./calc.php">Income and Expenses</a>
  <a href="./transaction.php">Transactions</a>


</nav>

<h1>ISSUE ITEM</h1>
<hr class="line">
<br>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function() {
    $('#issue-form').submit(function(e) {
      e.preventDefault();
      $.ajax({
        type: 'POST',
        url: 'issue.php',

        data: $(this).serialize(),
        success: function(response) {
          $('#message').html(response);
          $('#quantity-div').text(''); // Clear the existing quantity
          $("#issue-form")[0].reset();
        }
      });
    });

    // Update quantity label when item is selected
    $('select[name="name"]').on('change', function() {
      var itemName = $(this).val();
      if (itemName !== '') {
        $.ajax({
          type: 'POST',
          url: 'get_quantity.php',
          data: {name: itemName},
          success: function(response) {
            $('#quantity-div').text('Existing quantity: ' + response);
          }
        });
      } else {
        $('#quantity-div').text('');
      }
    });
    

   // Clear button click event
   $('#clear-button').click(function() {
        $('#issue-form')[0].reset();
        $('#quantity-div').text('');
        $('#alert-message').hide(); // Hide the alert message
    });
});
</script>

<form id="issue-form" action="backend/issue.php" method="post">
  <label>Item Name:</label>
  <select name="name" required>
    <option value="">Select an item</option>
    <?php
      // Require database file
      require_once('database.php');

      // Fetch items from the database
      $sql = "SELECT name FROM materials";
      $result = mysqli_query($mysqli, $sql);

      // Display items in a dropdown list
      if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
          echo "<option value='" . $row["name"] . "'>" . $row["name"] . "</option>";
        }
      }

      // Close database connection
      mysqli_close($mysqli);
    ?>
  </select>
  <div id="alert-message" style="display: none;"></div>

  <div id="quantity-div"></div>
  <br>
  <label>Quantity:</label>
  <input type="number" name="quantity" required>
  <br>
  <label>Selling Price:</label> <!-- New label for selling price -->
  <input type="number" name="selling_price" step="0.01" required> <!-- Input field for selling price -->
  <br>
  <div class="button-group">
    <input type="submit" value="Issue">
    <input type="button" id="clear-button" value="Clear">
  </div>
</form>

<div id="message"></div>

</body>
</html>

