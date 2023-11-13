<?php
require_once('database.php');// Include the database connection file

// Initialize the response variable
$response = [];

// Check if an action is specified
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    // Check the action type
    switch ($action) {
        case 'add':
            // Check if the form is submitted (for adding employees)
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["employee_name"]) && isset($_POST["salary"])) {
                // Retrieve form data
                $employee_name = $_POST["employee_name"];
                $salary = $_POST["salary"];

                // Insert data into the database
                $sql = "INSERT INTO employees (employee_name, salary) VALUES (?, ?)";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("sd", $employee_name, $salary);

                if ($stmt->execute()) {
                    // Success message
                    $response["message"] = "Employee added successfully.";
                } else {
                    // Error message
                    $response["error"] = "Error adding employee: " . $stmt->error;
                }

                // Close the prepared statement
                $stmt->close();
            }
            break;

        case 'delete':
            // Check if an employee name is provided for deletion
            if (isset($_POST["employee_name"])) {
                $employee_name = $_POST["employee_name"];

                // Delete the selected employee from the database
                $sql = "DELETE FROM employees WHERE employee_name = ?";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("s", $employee_name);

                if ($stmt->execute()) {
                    // Success message
                    $response["message"] = "Employee deleted successfully.";
                } else {
                    // Error message
                    $response["error"] = "Error deleting employee: " . $stmt->error;
                }

                // Close the prepared statement
                $stmt->close();
            }
            break;

        case 'load':
            // Check if we are loading employee names for the dropdown
            if (isset($_GET["dropdown"])) {
                // Fetch employee names from the database for the dropdown
                $employeeNames = [];
                $sql = "SELECT employee_name FROM employees";
                $result = $mysqli->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $employeeNames[] = $row["employee_name"];
                    }
                }

                // Add employee names to the response
                $response["employeeNames"] = $employeeNames;
            } else {
                // Fetch employee data from the database for the table
                $employeeData = [];
                $sql = "SELECT * FROM employees";
                $result = $mysqli->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $employeeData[] = $row;
                    }
                }

                // Add employee data to the response
                $response["employeeData"] = $employeeData;
            }
            break;

        default:
            // Invalid action
            $response["error"] = "Invalid action.";
    }
} else {
    // No action specified
    $response["error"] = "No action specified.";
}

// Close the database connection
$mysqli->close();

// Return the response as JSON
echo json_encode($response);
?>
