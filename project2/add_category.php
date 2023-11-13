<?php
session_start();

if (isset($_SESSION["user_id"])) {
    $mysqli = require_once(__DIR__ . '/database.php');


    $sql = "SELECT * FROM user
            WHERE id = {$_SESSION["user_id"]}";

    $result = $mysqli->query($sql);

    $user = $result->fetch_assoc();
}

// PHP code to retrieve categories from the database
$categories = array();
$query = "SELECT category FROM category";
$result = mysqli_query($mysqli, $query);
while ($row = mysqli_fetch_assoc($result)) {
    $categories[] = $row['category'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    <link rel="stylesheet" type="text/css" href="navbar.css">
    <title>Add/Delete Category Type</title>
    <style>
        /* Use flexbox to create a row layout */
        form {
            display: flex;
            align-items: center;
        }

        /* Add space between label and input */
        label {
            margin-right: 10px;
        }

        /* Push the buttons to the right */
        .button-container {
            margin-left: auto;
        }
    </style>
</head>
<body>
    <h2>Add/Delete Category Type</h2>
    <form id="submit-form">
        <!-- Place the buttons on the right -->
        <label for="category">Category Type:</label>
        <input type="text" id="category" name="category" placeholder="Enter new category" required>

        <button type="button" onclick="saveCategory()">Add Category</button>
        <!-- Back button using JavaScript to go back one page -->
        <div class="button-container">
            <button onclick="goBack()">Back</button>
        </div>
    </form>

    <h2>Delete Category</h2>
    <form id="delete-category-form">
        <label for="delete-category">Select Category :</label>
        <select name="delete-category" id="delete-category" required>
            <option value="">Select a category to delete</option>
            <!-- PHP code to display categories from the database -->
            <?php
            foreach ($categories as $category) {
                echo '<option value="' . $category . '">' . $category . '</option>';
            }
            ?>
        </select>
        <button type="button" onclick="deleteCategory()">Delete Category</button>
    </form>

    <!-- JavaScript function to navigate back one page -->
    <script>
        function goBack() {
            window.history.back();
        }

        function saveCategory() {
    const category = document.getElementById("category").value.trim(); // Trim whitespace
    if (category === "") {
        alert("Please enter a category before adding.");
        return;
    }

    const formData = new FormData();
    formData.append("category", category);

    fetch("save_category.php", {
        method: "POST",
        body: formData
    })
    .then(response => {
        if (response.ok) {
            return response.text(); // Parse response text
        } else {
            throw new Error("Failed to add the category.");
        }
    })
    .then(data => {
        alert(data); // Display success message
        document.getElementById("category").value = ""; // Clear input field
    })
    .catch(error => {
        alert(error.message); // Display error message
    });
}

        function deleteCategory() {
        const selectedCategory = document.getElementById("delete-category").value;
        if (selectedCategory === "") {
            alert("Please select a category to delete.");
            return;
        }

        fetch("delcat.php?category=" + encodeURIComponent(selectedCategory), {
            method: "GET"
        })
            .then(response => {
                if (response.ok) {
                    return response.text();
                } else {
                    throw new Error("Failed to delete the category. Please try again.");
                }
            })
            .then(data => {
                alert(data);
                // Optionally, you can reload the page or perform any other necessary action
            })
            .catch(error => {
                alert(error.message);
            });
    }
    </script>
</body>
</html>
