<?php
require_once('database.php');  // Include the database connection file

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $employee_name = $_POST["employee_name"];
    $salary = $_POST["salary"];

    // Insert data into the database
    $sql = "INSERT INTO employees (employee_name, salary) VALUES (?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("sd", $employee_name, $salary);
    $stmt->execute();

    // Close the prepared statement
    $stmt->close();
}

// Fetch data from the database and generate the HTML table
$sql = "SELECT * FROM employees";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    echo '<table border="1">';
    echo '<tr>';
    echo '<th>ID</th>';
    echo '<th>Employee Name</th>';
    echo '<th>Salary</th>';
    echo '</tr>';
    
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $row["id"] . '</td>';
        echo '<td>' . $row["employee_name"] . '</td>';
        echo '<td>' . $row["salary"] . '</td>';
        echo '</tr>';
    }

    echo '</table>';
} else {
    echo '<p>No data available</p>';
}

// Close the database connection (no need to do this again)
$mysqli->close();
?>
