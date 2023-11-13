
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


    <title>Income and Expenses Tracker</title>
    <link rel="icon" href="/icon image/RDGRP.ico" type="image/x-icon">

    <style>
        table {
            width: 100%;
        }
        th, td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        button {
            margin-top: 10px;
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
    <a href="update.php">Update Item</a>
	<a href="./inventory.php">Inventory</a>
    <a class="active" href="./calc.php">Income and Expenses</a>
    <a href="./transaction.php">Transactions</a>


		
	</nav>
    <h1>INCOME & EXPENSES</h1>
    <hr class="line">
    
    <div>
        <h2>Income</h2>
        <table>
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody id="incomeTableBody">
                <tr>
                    <td><input type="text" name="incomeType[]" placeholder="Income Type"></td>
                    <td><input type="number" name="incomeAmount[]" placeholder="Amount"></td>
                </tr>
            </tbody>
        </table>
        <button onclick="addIncomeRow()">Add Income Row</button>
    </div>
    
    <div>
        <h2>Expenses</h2>
        <table>
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody id="expensesTableBody">
                <tr>
                    <td><input type="text" name="expensesType[]" placeholder="Expenses Type"></td>
                    <td><input type="number" name="expensesAmount[]" placeholder="Amount"></td>
                </tr>
            </tbody>
        </table>
        <button onclick="addExpensesRow()">Add Expenses Row</button>
    </div>
    <div>
    <h2>Calculated Totals</h2>
    <p>Total Income: Rs <span id="totalIncome">0.00</span></p>
    <p>Total Expenses: Rs <span id="totalExpenses">0.00</span></p>
    </div>
    
    <button onclick="calculateTotal()">Calculate Total</button>
    <button onclick="printSummaryReport()">Print Summary Report</button>
    <button onclick="clearTables()">Clear Tables</button>

    
    <script>
        window.addEventListener('load', function() {
            loadSavedData();
        });

        function loadSavedData() {
            var savedIncomeTypes = localStorage.getItem('incomeTypes');
            var savedIncomeAmounts = localStorage.getItem('incomeAmounts');
            var savedExpensesTypes = localStorage.getItem('expensesTypes');
            var savedExpensesAmounts = localStorage.getItem('expensesAmounts');


            // Populate tables using the loaded data
            // Example: parse JSON and update table content
        }

        function saveDataToLocalStorage() {
            var incomeTypes = [];  // Collect income types
            var incomeAmounts = [];  // Collect income amounts
            var expensesTypes = [];  // Collect expenses types
            var expensesAmounts = [];  // Collect expenses amounts

            // Code to collect data from tables and populate the arrays

            // Save arrays as JSON strings in Local Storage
            localStorage.setItem('incomeTypes', JSON.stringify(incomeTypes));
            localStorage.setItem('incomeAmounts', JSON.stringify(incomeAmounts));
            localStorage.setItem('expensesTypes', JSON.stringify(expensesTypes));
            localStorage.setItem('expensesAmounts', JSON.stringify(expensesAmounts));
        }

    function addIncomeRow() {
        var newRow = '<tr><td><input type="text" name="incomeType[]" placeholder="Income Type"></td><td><input type="number" name="incomeAmount[]" placeholder="Amount"></td></tr>';
        document.getElementById('incomeTableBody').insertAdjacentHTML('beforeend', newRow);
    }

    function addExpensesRow() {
        var newRow = '<tr><td><input type="text" name="expensesType[]" placeholder="Expenses Type"></td><td><input type="number" name="expensesAmount[]" placeholder="Amount"></td></tr>';
        document.getElementById('expensesTableBody').insertAdjacentHTML('beforeend', newRow);
    }

    function calculateTotal() {
        var incomeRows = document.querySelectorAll('[name="incomeAmount[]"]');
        var expensesRows = document.querySelectorAll('[name="expensesAmount[]"]');
        
        var totalIncome = 0;
        var totalExpenses = 0;

        incomeRows.forEach(function(input) {
            totalIncome += parseFloat(input.value || 0);
        });

        expensesRows.forEach(function(input) {
            totalExpenses += parseFloat(input.value || 0);
        });

        document.getElementById('totalIncome').textContent = totalIncome.toFixed(2);
        document.getElementById('totalExpenses').textContent = totalExpenses.toFixed(2);
    }
    function printSummaryReport() {
    var incomeRows = document.querySelectorAll('[name="incomeType[]"]');
    var incomeAmounts = document.querySelectorAll('[name="incomeAmount[]"]');
    var expensesRows = document.querySelectorAll('[name="expensesType[]"]');
    var expensesAmounts = document.querySelectorAll('[name="expensesAmount[]"]');

    // Create a border around the entire report
    var summaryReport = "<div style='font-family: Arial, sans-serif; padding: 20px; border: 2px solid #000;'>"; // Added border styling
    summaryReport += "<h1 style='text-align: center;'>R & D Animal Food Supplies Summary Report</h1>";

    // Styling for the Income section
    summaryReport += "<div style='margin-top: 20px;'>";
    summaryReport += "<h2>Income</h2>";
    summaryReport += "<ul style='list-style-type: disc;'>";
    for (var i = 0; i < incomeRows.length; i++) {
        summaryReport += "<li><strong>" + incomeRows[i].value + ":</strong> Rs " + incomeAmounts[i].value + "</li>";
    }
    summaryReport += "</ul>";
    summaryReport += "</div>";

    // Styling for the Expenses section
    summaryReport += "<div style='margin-top: 20px;'>";
    summaryReport += "<h2>Expenses</h2>";
    summaryReport += "<ul style='list-style-type: disc;'>";
    for (var i = 0; i < expensesRows.length; i++) {
        summaryReport += "<li><strong>" + expensesRows[i].value + ":</strong> Rs " + expensesAmounts[i].value + "</li>";
    }
    summaryReport += "</ul>";
    summaryReport += "</div>";

    var totalIncome = parseFloat(document.getElementById('totalIncome').textContent);
    var totalExpenses = parseFloat(document.getElementById('totalExpenses').textContent);

    // Styling for the Totals section
    summaryReport += "<div style='margin-top: 20px; text-align: center;'>";
    summaryReport += "<h2>Total Income: Rs " + totalIncome.toFixed(2) + "</h2>";
    summaryReport += "<h2>Total Expenses: Rs " + totalExpenses.toFixed(2) + "</h2>";
    summaryReport += "</div>";

    summaryReport += "</div>"; // Close the border-div

    var printWindow = window.open('', '_blank');
    printWindow.document.write(summaryReport);
    printWindow.document.close();
    printWindow.print();
}


    function clearTables() {
    // Clear the table content
    var incomeTableBody = document.getElementById('incomeTableBody');
    var expensesTableBody = document.getElementById('expensesTableBody');
    
    incomeTableBody.innerHTML = '';
    expensesTableBody.innerHTML = '';

    // Save the cleared state to Local Storage
    saveDataToLocalStorage();

    // Reload the page to reflect cleared tables
    location.reload();
}


</script>

</body>
</html>
