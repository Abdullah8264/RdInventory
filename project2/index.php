<?php

session_start();

if (isset($_SESSION["user_id"])) {
    
    $mysqli = require_once(__DIR__ . '/database.php');

    
    $sql = "SELECT * FROM user
            WHERE id = {$_SESSION["user_id"]}";
            
    $result = $mysqli->query($sql);
    
    $user = $result->fetch_assoc();
}

?>
<!DOCTYPE html> 
<html>
<head>
    <title>Home</title>
    <link rel="icon" href="/icon image/RDGRP.ico" type="image/x-icon">

    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<style>
    /* Style for the buttons */
    .button {
        background-color: blue;
        padding: 10px 20px; /* Adjust the padding as needed */
        display: inline-block;
        margin-right: 10px; /* Adjust the margin between buttons as needed */
        text-decoration: none;
        color: white; /* Set the button text color */
        border: 1px solid none; /* Add a border */
        border-radius: 5px; /* Add rounded corners */
    }
</style>
<body>
    
    <h1>Home</h1>
    
    <?php if (isset($user)): ?>
        
        <p>Hello <?= htmlspecialchars($user["name" ]) ?>...!</p>
        
        
<a href="start.php" class="button">Get Started</a>
<a href="login.php" class="button">Log out</a>
        
    <?php else: ?>
        
        <p><a href="login.php">Log in</a> or <a href="signup.html">sign up</a></p>
        
    <?php endif; ?>
    
</body>
</html>
    
    
    
    
    
    
    
    
    
    
    