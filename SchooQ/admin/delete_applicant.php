<?php
require_once 'config.php';
$page_title = 'Delete Applicant';
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

// Prepare a statement for deletion to prevent SQL injection
$stmt = $conn->prepare("DELETE FROM interested_applicants WHERE id = ?");
$stmt->bind_param("i", $applicantId);

// Confirm deletion only if requested through a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt2 = $conn->prepare("DELETE FROM follow_up_remarks WHERE applicant_id = ?");
    $stmt2->bind_param("i", $applicantId);

    if ($stmt2->execute()) {
        // If follow-up remarks are deleted successfully, proceed with applicant deletion
        if ($stmt->execute()) {
            header("location: index.php?deleted=true"); // Redirect to portal with success message
        } else {
            echo "Error deleting applicant: " . $stmt->error;
        }
    } else {
        echo "Error deleting follow-up remarks: " . $stmt2->error;
    }
    } else {
    ?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="css/style.css">
    <style>
        body {
    font-family: sans-serif;
    text-align: center;
    background-color: #f5f5f5; /* Light gray background */
}


p {
    font-size: 16px;
    color: #666;
    margin-bottom: 20px;
}

form {
    background-color: #fff; /* White background for contrast */
    border: 1px solid #ccc;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Soft shadow for depth */
}

button {
    background-color: #e74c3c; /* Red background for emphasis */
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
}

button:hover {
    background-color: #c0392b; /* Slightly darker red on hover */
}

    </style>
</head>
<body>
<div class="container" style="margin: 10px; margin-top: 20px;">
    <form method="post">
    <p>Are you sure you want to delete the applicant with ID <?php echo $applicantId; ?>? This action cannot be undone.</p>
        <button type="submit">Confirm Deletion</button>
    </form>
</div>
</body>
</html>

<?php
}

$stmt->close();
$conn->close();
?>
