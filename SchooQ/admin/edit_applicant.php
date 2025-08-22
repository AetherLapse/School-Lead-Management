<?php
require_once 'config.php';
$page_title = 'Edit Applicant';
include 'header.php';
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: Login.php"); // Redirect to login page if not logged in
    exit();
}


// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the applicant ID from the URL parameter
$applicantId = $_GET['id'];

// Fetch applicant data
$stmt = $conn->prepare("SELECT * FROM interested_applicants WHERE id = ?");
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}

$stmt->bind_param("i", $applicantId);
$stmt->execute();
$result = $stmt->get_result();
$applicant = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate user input
    $fullName = $_POST['full_name'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phone_number'];
    $class = $_POST['applying_for_class'];
    $query = $_POST['query'];

    // Prepare update statement for applicant data
    $stmt = $conn->prepare("UPDATE interested_applicants SET full_name = ?, email = ?, phone_number = ?, applying_for_class = ?, query = ? WHERE id = ?");
    if (!$stmt) {
        die("Error preparing update statement: " . $conn->error);
    }
    $stmt->bind_param("sssssi", $fullName, $email, $phoneNumber, $class, $query, $applicantId);

    if ($stmt->execute()) {
        header("location: index.php?updated=true"); // Redirect to portal with success message
    } else {
        die("Error updating applicant: " . $stmt->error);
    }
    $stmt->close();

}


if (!$applicant) {
    die("Applicant not found.");
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="css/style.css">
    <style>
        body {
  font-family: sans-serif;
  background-color: #f5f5f5;
}

form {
  background-color: #fff;
  padding: 20px;
  border-radius: 5px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  width: 600px;
  margin: 0 auto;
}

label {
  display: block;
  margin-bottom: 5px;
  font-weight: bold;
}

input, textarea {
  width: 95%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 3px;
  font-size: 16px;
}

textarea {
  height: 100px;
}

button {
  background-color: #e74c3c;
  color: #fff;
  border: none;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
  margin-top: 20px;
  display: block;
  margin-left: auto;
  margin-right: auto;
}

button:hover {
  background-color: #c0392b;
}

.disabled {
  opacity: 0.5;
  cursor: default;
}

    </style>
</head>
<body>
<div class="container" style="margin: 10px; margin-top: 20px;">
    <form method="post">
    <label for="name">Full Name:</label>
        <input type="text" id="name" name="full_name" value="<?php echo $applicant['full_name']; ?>"><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $applicant['email']; ?>"><br><br>

        <label for="phone_number">Phone Number:</label>
        <input type="tel" id="phone_number" name="phone_number" value="<?php echo $applicant['phone_number']; ?>"><br><br>

        <label for="class">For Class:</label>
        <input type="text" id="class" name="applying_for_class" value="<?php echo $applicant['applying_for_class']; ?>"><br><br>

        <label for="query">Query:</label>
        <textarea name="query" id="query" cols="30" rows="5"><?php echo $applicant['query']; ?></textarea><br><br>

        <label for="date_created">Date Created:</label>
        <input type="text" id="date_created" name="date_submitted" value="<?php echo $applicant['date_submitted']; ?>" disabled>(Read-only)<br><br>

        <button>Update Applicant</button>
    </form>
</div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
