<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["user_id"])) {
    // Redirect to login form if the user is not logged in
    header("Location: login.php");
    exit;
}

// Connect to the database
require_once('database.php');

// Get form data
$item_name = mysqli_real_escape_string($mysqli, $_POST['name']);
$quantity = mysqli_real_escape_string($mysqli, $_POST['quantity']);
$selling_price = mysqli_real_escape_string($mysqli, $_POST['selling_price']);

// Validate form data
if (empty($item_name) || empty($quantity) || empty($selling_price)) {
    echo json_encode(array("status" => "error", "message" => "Please fill out all fields"));
    exit();
}

if (!is_numeric($quantity) || !is_numeric($selling_price)) {
    echo json_encode(array("status" => "error", "message" => "Quantity and Selling Price must be numbers"));
    exit();
}

// Check if item exists
$sql = "SELECT * FROM materials WHERE name='$item_name'";
$result = mysqli_query($mysqli, $sql);

if (mysqli_num_rows($result) == 0) {
    echo json_encode(array("status" => "error", "message" => "Item not found"));
    exit();
}

// Update item quantity
$row = mysqli_fetch_assoc($result);
$current_quantity = $row['quantity'];

if ($current_quantity < $quantity) {
    echo json_encode(array("status" => "error", "message" => "Not enough $item_name in inventory"));
    exit();
}

$new_quantity = $current_quantity - $quantity;

// Use transactions for data consistency
mysqli_begin_transaction($mysqli);

// Insert transaction
$transaction_query = "INSERT INTO transactions (item_name, quantity, selling_price) 
                      VALUES (?, ?, ?)";
$stmt = mysqli_prepare($mysqli, $transaction_query);
mysqli_stmt_bind_param($stmt, "ssd", $item_name, $quantity, $selling_price);

if (mysqli_stmt_execute($stmt)) {
    $update_query = "UPDATE materials SET quantity=? WHERE name=?";
    $update_stmt = mysqli_prepare($mysqli, $update_query);
    mysqli_stmt_bind_param($update_stmt, "is", $new_quantity, $item_name);

    if (mysqli_stmt_execute($update_stmt)) {
        mysqli_commit($mysqli); // Commit the transaction
        // Display a success alert message with quantity and item name
        echo "<script>alert('$quantity Kg of  $item_name issued successfully.');</script>";
    } else {
        mysqli_rollback($mysqli); // Rollback the transaction
        // Display an error alert message
        echo "<script>alert('Error updating record.');</script>";
    }

    mysqli_stmt_close($update_stmt);
} else {
    mysqli_rollback($mysqli); // Rollback the transaction
    // Display an error alert message
    echo "<script>alert('Error inserting transaction.');</script>";
}




mysqli_stmt_close($stmt);
mysqli_close($mysqli);
?>