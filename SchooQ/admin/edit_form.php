<?php
require_once 'config.php';
$page_title = 'Edit/Delete';
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

$id = $_GET['id'];


function hasFollowUpDependencies($id) {
  global $conn;

  $sql = "SELECT * FROM follow_up_remarks WHERE follow_up_status = ?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "s", $id);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  if (mysqli_num_rows($result) > 0) {
      mysqli_stmt_close($stmt);
      return true;  // Master Name has dependencies
  } else {
      mysqli_stmt_close($stmt);
      return false;
  }
}

$hasDependencies = hasFollowUpDependencies($id);

// If the delete button is clicked
if (isset($_POST['delete'])) {
  // Prepare a DELETE query
  $sql = "DELETE FROM master WHERE ID = ?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "i", $id); // Bind the ID parameter
  echo "<p>Cannot delete Master Name because it's currently in use in Follow-Ups.</p>";
  // Execute the query
  if (mysqli_stmt_execute($stmt)) {
    echo "Entry deleted successfully!";
    // Redirect to a success page or master table view
    header("Location: master.php");
    exit();
  } else {
    echo "Error deleting entry: " . mysqli_error($conn);
  }

  mysqli_stmt_close($stmt);
}

// If the form is submitted, process the updated data
if (isset($_POST['update'])) {
    $a_value = mysqli_real_escape_string($conn, $_POST['a']); // Sanitize input
  
    // Prepare an UPDATE query
    $sql = "UPDATE master SET A = ? WHERE ID = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $a_value, $id); // Bind parameters
  
    if (mysqli_stmt_execute($stmt)) {
      echo "Entry updated successfully!";
      // Redirect to master table or success page
      header("Location: master.php");
      exit();
    } else {
      echo "Error updating entry: " . mysqli_error($conn);
    }
  
    mysqli_stmt_close($stmt);
  } else {
    // Fetch existing data for the entry to populate the form
    $sql = "SELECT A FROM master WHERE ID = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
  
    
  $row = mysqli_fetch_assoc($result);
    $a_value = $row['A']; // Get the existing value of "A"
    mysqli_stmt_close($stmt);
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/style.css" rel="stylesheet">
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

main {
  max-width: 960px;
  padding: 20px;
  background-color: #fff;
  border-radius: 5px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

label {
  font-weight: bold;
  margin-bottom: 5px;
  width: auto; /* Adjust as needed */
}

input, textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
  box-sizing: border-box;
}

.delete-button {
    color: white;
    background-color: red;
    padding-top: 10px;
    padding-right: 15px;
    padding-left: 15px;
    padding-bottom: 10px;
    border: none;
    border-radius: 5px;
    float: right;
    text-decoration: none;

}

#a {
  /* Add any specific styling for the "A" input field here */
}

.error {
  color: red;
  font-weight: bold;
  margin-bottom: 10px;
}

.form {
  width: 250px; /* Adjust width as needed */
  margin: 0 auto; /* Center the form */
  padding: 20px;
  border-radius: 5px;
background: white;
box-shadow:  5px 5px 15px #bebebe,
             -5px -5px 15px #ffffff;
             height: fit-content;
}

#disabled {
  background-color: grey;
}
    </style>
</head>
<body>
<div class="container" style="margin: 10px; margin-top: 20px;">
    <form action="edit_form.php?id=<?php echo $id; ?>" method="POST" style="display: inline; background: none; padding: none; margin: none;">
  <button type="submit" name="delete" class="delete-button" style="float: right;" <?php if ($hasDependencies) { echo 'disabled id="disabled"'; } ?>>Delete</button>
</form>
<script type="text/javascript">
  dis = document.getElementById("disabled");
  dis.innerHTML = "In Use"

</script>
<form action="edit_form.php?id=<?php echo $id; ?>" method="POST" class="form">
    <label for="">Serial No.</label><?php echo $id; ?>
  <label for="a">Enter new value for Name:</label>
  <input type="text" id="a" name="a" value="<?php echo $a_value; ?>" required>
  <button type="submit" name="update" style="margin-top: 10px;">Update Entry</button>
</form>
</div>
</body>
</html>