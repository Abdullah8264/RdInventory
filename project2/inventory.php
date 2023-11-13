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
	<link rel="stylesheet" type="text/css" href="navbar.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">


    <title>Inventory</title>
	<link rel="icon" href="/icon image/RDGRP.ico" type="image/x-icon">

	<style>
		body {
			font-family: Arial, sans-serif;
		}

		table  {
			border-collapse: collapse;
			width: 100%;
			margin-top: 20px;
			border-radius: 10px;
			

			
		
		}

		table th, table td {
			border: 1px solid #ddd;
			padding: 8px;
		}

		table th {
			background-color:darkcyan;
			color: #000;
			
		}

		.line {
			width: 50%;
			margin-left: 0;
			background-color: #ddd;
			margin-top: 5px;
			
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
		<a href="./update.php">Update Item</a>
		<a class="active" href="./inventory.php">Inventory</a>
		<a href="./calc.php">Income and Expenses</a>
		<a href="./transaction.php">Transactions</a>


	</nav>
	<h1>INVENTORY</h1>
	<hr class="line">
	
	<table>
		<tr>
			<th>Name</th>
			<th>Quantity</th>
			<th>Category</th>
			<th>Price</th>
			
		</tr>
		<?php

			require_once('database.php');


		// Retrieve data from the database
		$sql = "SELECT * FROM materials";
		$result = $mysqli->query($sql);

		// Display data in a table format
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				echo "<tr>";
				echo "<td>" . $row["name"]. "</td>";
				echo "<td>" . $row["quantity"]. "</td>";
				echo "<td>" . $row["category"]. "</td>";
				echo "<td>" . $row["price"]. "</td>";
				
				echo "</tr>";
			}
		} else {
			echo "<tr><td colspan='4'>0 results</td></tr>";
		}

		$mysqli->close();
		?>
	</table>
</body>
</html>