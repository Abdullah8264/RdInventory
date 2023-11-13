<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION["user_id"])) {
    // Redirect the user to the login page
    header("Location: login.php");
    exit();
}

// Include the database connection or any other required files
$mysqli = require_once(__DIR__ . '/database.php');

// Query to retrieve user information (adjust as needed)
$sql = "SELECT * FROM user WHERE id = {$_SESSION["user_id"]}";
$result = $mysqli->query($sql);

// Fetch the user data
if ($result) {
    $user = $result->fetch_assoc();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    <link rel="stylesheet" type="text/css" href="navbar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">


    <title>Transactions</title>
    <link rel="icon" href="/icon image/RDGRP.ico" type="image/x-icon">


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
    .line {
			width: 50%;
			margin-left: 0;
			background-color: #ddd;
			margin-top: 5px;
			
		}
        h2 {
            text-align: center;
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
    <a href="update.php">Update Item</a>
	<a href="./inventory.php">Inventory</a>
    <a href="./calc.php">Income and Expenses</a>
    <a class="active" href="./transaction.php">Transactions</a>

		
	</nav>
    <h1>EMPLOYEE SALARY INPUT</h1>
    <hr class="line">

<form id="employeeForm">
    <label for="employee_name">Name:</label>
    <input type="text" id="employee_name" name="employee_name" required>

    <label for="salary">Salary:</label>
    <input type="number" id="salary" name="salary" required><br>

    <input type="submit" value="Submit">
</form>

<h2>Delete Employee</h2>
<!-- Dropdown list of employees -->
<select id="employeeDropdown"></select>
    <!-- Employee names will be populated here dynamically -->


<!-- Button to delete selected employee -->
<button id="deleteEmployee">Delete Employee</button>

<h2>Employee Info</h2>
<!-- Display the employee data table -->
<table border="1" id="employeeTable">
    <tr>
        <th>ID</th>
        <th>Employee Name</th>
        <th>Salary</th>
    </tr>
    <!-- Employee data will be populated here dynamically -->
</table>

<div id="message"></div>

<script>
   $(document).ready(function() {
    // Function to load employee names into the dropdown
    function loadEmployeeNames() {
        $.ajax({
            type: "GET",
            url: "employee_management.php?action=load&dropdown=1", // Include action parameter and dropdown=1
            dataType: "json", // Expect JSON response
            success: function(response) {
                if (response.employeeNames) {
                    var dropdown = $("#employeeDropdown");
                    dropdown.empty();
                    $.each(response.employeeNames, function(index, name) {
                        dropdown.append($('<option>').text(name));
                    });
                }
            }
        });
    }

    // Call loadEmployeeNames function to populate the dropdown
    loadEmployeeNames();

   

        // Function to load and display the employee table
        function loadAndDisplayEmployeeTable() {
            $.ajax({
                type: "GET",
                url: "employee_management.php?action=load", // Include action parameter
                dataType: "json", // Expect JSON response
                success: function(response) {
                    if (response.employeeData) {
                        var table = $("#employeeTable");
                        table.find("tbody").empty(); // Clear the table body

                        $.each(response.employeeData, function(index, employee) {
                            var row = $("<tr>");
                            row.append($("<td>").text(employee.id));
                            row.append($("<td>").text(employee.employee_name));
                            row.append($("<td>").text(employee.salary));
                            table.append(row);
                        });
                    }
                }
            });
        }

        // Load employee names and table on page load
        loadEmployeeNames();
        loadAndDisplayEmployeeTable(); // Initial load

    

    // Function to load and display the employee table
    function loadAndDisplayEmployeeTable() {
        $.ajax({
            type: "GET",
            url: "employee_management.php?action=load", // Include action parameter
            dataType: "json", // Expect JSON response
            success: function(response) {
                if (response.employeeData) {
                    var table = $("#employeeTable");
                    table.find("tbody").empty(); // Clear the table body

                    $.each(response.employeeData, function(index, employee) {
                        var row = $("<tr>");
                        row.append($("<td>").text(employee.id));
                        row.append($("<td>").text(employee.employee_name));
                        row.append($("<td>").text(employee.salary));
                        table.append(row);
                    });
                }
            }
        });
    }

    // Load employee names and table on page load
    loadEmployeeNames();
    loadAndDisplayEmployeeTable(); // Initial load

    $("#employeeForm").submit(function(event) {
        event.preventDefault(); // Prevent the default form submission

        // Serialize the form data
        var formData = $(this).serialize();

        // Send an AJAX POST request to employee_management.php
        $.ajax({
            type: "POST",
            url: "employee_management.php?action=add", // Include action parameter
            data: formData, // Serialize the form data correctly
            dataType: "json", // Expect JSON response
            success: function(response) {
                // Display a success or error message
                if (response.message) {
                    $("#message").text(response.message).css("color", "green");
                } else if (response.error) {
                    $("#message").text(response.error).css("color", "red");
                }

                // Clear the form fields
                $("#employee_name").val("");
                $("#salary").val("");

                // Reload and display the employee table
                loadAndDisplayEmployeeTable();
                loadEmployeeNames();
            }
        });
    });

    // Handle delete button click
$("#deleteEmployee").on("click", function() {
    var selectedEmployee = $("#employeeDropdown").val();

    // Check if an employee is selected
    if (selectedEmployee) {
        // Send an AJAX POST request to employee_management.php
        $.ajax({
            type: "POST",
            url: "employee_management.php?action=delete", // Include action parameter
            data: { employee_name: selectedEmployee }, // Send the selected employee name
            dataType: "json", // Expect JSON response
            success: function(response) {
                // Display a success or error message
                if (response.message) {
                    $("#message").text(response.message).css("color", "green");
                } else if (response.error) {
                    $("#message").text(response.error).css("color", "red");
                }

                // Reload and display the employee table
                loadAndDisplayEmployeeTable();
                loadEmployeeNames();
            }
        });
    }
});

});

   
</script>

</body>
</html>
