<?php

require_once 'config.php';
$page_title = 'Add Master';
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

if (isset($_POST['submit'])) {
    $a_value = mysqli_real_escape_string($conn, $_POST['a']); // Sanitize input
  
    $sql = "INSERT INTO master (A) VALUES ('$a_value')";
  
    if (mysqli_query($conn, $sql)) {
        header("location: master.php");
    } else {
      echo "Error: " . mysqli_error($conn);
    }
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Master</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
  font-family: sans-serif;
  margin: 0;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background-color: #f0f0f0;
  overflow: hidden;
}

form {
  width: 250px; /* Adjust width as needed */
  margin: 0 auto; /* Center the form */
  padding: 20px;
  border-radius: 5px;
background: white;
box-shadow:  5px 5px 15px #bebebe,
             -5px -5px 15px #ffffff;
}

label {
  font-weight: bold;
  margin-bottom: 5px;
  width: 150px; /* Adjust as needed */
}

input, textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
  box-sizing: border-box;
}

button {
    margin-top: 10px;
    display: block;
    width: 80px;
    height: 40px;

}

.error {
  color: red;
  font-weight: bold;
  margin-bottom: 10px;
}

    </style>
</head>
<body>
<div class="container" style="margin: 10px; margin-top: 20px;">
    <form action="add_master.php" method="POST">
  <label for="a">Enter Name:</label>
  <input type="text" id="a" name="a" required>
  <button type="submit" name="submit">Add Data</button>
</form>
</div>
</body>
</html>