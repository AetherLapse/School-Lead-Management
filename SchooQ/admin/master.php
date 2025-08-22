<?php

require_once 'config.php';
$page_title = 'Master Data';
include 'header.php';
session_start();


if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: Login.php"); // Redirect to login page if not logged in
    exit();
}

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM master";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Master Data</title>
</head>
<body>
<div class="container" style="margin: 10px; margin-top: 20px;">
    <table border="1">
        <thead>
            <tr>
                <th>Serial No.</th>
                <th>Name</th>
                <th>Actions</th>
                </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<td>" . $row['ID'] . "</td>";
                    echo "<td>" . $row['A'] . "</td>";
                    echo "<td><a href='edit_form.php?id=" . $row['ID'] . "' class='follow-up-button'>Edit/Delete</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No data found</td></tr>";
            }
            ?>
        </tbody>
    </table>
        </div>
        <script>
            const nav = document.getElementById('list');

// Create the new item HTML string
const newItem = "<li><a href='add_master.php'>Add New</a></li>";

nav.innerHTML += newItem;
    </script>
</body>
</html>