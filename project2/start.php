<?php
// Start the session
session_start();

// Check if the user is not logged in
if (!isset($_SESSION["user_id"])) {
    // Redirect to the login page or perform any other necessary action
    header("Location: login.php");
    exit; // Make sure to exit after redirection
}

// Include the database connection file at the beginning
$mysqli = require_once(__DIR__ . '/database.php');

?>


<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
<link rel="stylesheet" type="text/css" href="navbar.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">



		
  <title>Add Item</title>
  <link rel="icon" href="/icon image/RDGRP.ico" type="image/x-icon">

  <style>
    body {
			font-family: Arial, sans-serif;
		}
  
  .button {
    display: inline-block;
    padding: 5px 20px;
    background-color: #e0e0e0;
    color: #000000;
    text-decoration: none;
    transition: background-color 0.2s ease;
    border-radius: 10px;
  }
  .button_addcat {
    display: inline-block;
    padding: 5px 20px;
    background-color: #e0e0e0;
    color: #000000;
    text-decoration: none;
    transition: background-color 0.2s ease;
    border-radius: 10px;
    text-decoration: none; /* For modern browsers */
    cursor: pointer; /* Optional: To indicate that it's clickable */
  }
  
  .button:hover {
    background-color:indianred;
  }
  .button_addcat:hover {
    background-color:#54f063;
  }
  
  
  .right-align {
    text-align: right;
  }
  .line {
  width: 50%;
  margin-left: 0;
  color: white;
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
		<a class="active" href="./start.php">Add Item</a>
		<a href="./issue_item.php">Issue Item</a>
    <a href="update.php">Update Item</a>
		<a href="./inventory.php">Inventory</a>
    <a href="./calc.php">Income and Expenses</a>
    <a href="./transaction.php">Transactions</a>

		
	</nav>
  <h1>INSERT ITEM</h1>
  <hr class="line">
  <div class="right-align">
  <a href="add_category.php" class="button_addcat">Add Category</a>
  
</div>

  <form id="submit-form" action="" method="post">
    <label>Name:</label>
    <input type="text" name="name" required>
    <br>
    <label>Quantity:</label>
    <input type="number" name="quantity" required>
    <br>
    <?php
    $query = "SELECT category FROM category";
$result = mysqli_query($mysqli, $query);
if (!$result) {
    die("Query failed: " . mysqli_error($mysqli));
}
?>

<!-- Step 4: Output the HTML -->
<label>Category:</label>
<select name="category" required>
<option value="">Select a category</option>
    <!-- Step 3: Generate the dropdown options -->
    <?php
    while ($row = mysqli_fetch_assoc($result)) {
        $category = $row['category'];
        echo "<option value='$category'>$category</option>";
    }
    ?>
</select>
<br>
    <label>Price:</label>
    <input type="number" step="0.01" name="price" required>
    <br>
    
    <br>
    <input type="submit" value="Submit">
    
  </form>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function() {
      $("#submit-form").on("submit", function(e) {
        e.preventDefault();
        $.ajax({
          url: "material.php",
          type: "post",
          data: $("#submit-form").serialize(),
          success: function(response) {
            $("#submit-form")[0].reset();
            alert(response);
          }
        });
      });
    });
  </script>
</body>
</html>
