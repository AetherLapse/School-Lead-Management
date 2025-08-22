<?php
require_once 'config.php';
$page_title = 'Create Applicant';
include 'header.php';

session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: Login.php"); // Redirect to login page if not logged in
    exit();
}


$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission if it has been submitted
if (isset($_POST['submit'])) {
    // Retrieve form data
    $fullName = filter_var($_POST['full_name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phoneNumber = filter_var($_POST['phone_number'], FILTER_SANITIZE_STRING);
    $applyingForClass = filter_var($_POST['applying_for_class'], FILTER_SANITIZE_STRING);
    $query = filter_var($_POST['query'], FILTER_SANITIZE_STRING);
    $dateSubmitted = date('Y-m-d'); // Set current date and time

    // Validate required fields (add more as needed)
    if (empty($fullName) || empty($email) || empty($applyingForClass)) {
        $errors[] = "Please fill in all required fields.";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address.";
    }

    // Handle errors (display and redirect or continue processing)
    if (!empty($errors)) {
        // Display error messages and reload form
        $error_message = "<ul>";
        foreach ($errors as $error) {
            $error_message .= "<li>$error</li>";
        }
        $error_message .= "</ul>";
    } else {
        // Prepare insert statement
        $stmt = $conn->prepare("INSERT INTO interested_applicants (full_name, email, phone_number, applying_for_class, query, date_submitted) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $fullName, $email, $phoneNumber, $applyingForClass, $query, $dateSubmitted);

        // Execute statement and handle success or failure
        if ($stmt->execute()) {
            header("location: index.php?created=true"); // Redirect to portal with success message
        } else {
            die("Error creating applicant: " . $stmt->error);
        }
        $stmt->close();
    }
}

// Close database connection
$conn->close();

?>
<head>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container" style="margin: 10px; margin-top: 20px;">
    <form method="post">
        <label for="name">Full Name:</label>
        <input type="text" id="name" name="full_name" required>
        <br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br><br>

        <label for="phone_number">Phone Number:</label>
        <input type="tel" id="phone_number" name="phone_number" required>
        <br><br>

        <label for="class">Applying For Class:</label>
        <input type="text" id="class" name="applying_for_class" required>
        <br><br>

        <label for="query">Query:</label>
        <textarea name="query" id="query" cols="30" rows="5"></textarea>
        <br><br>

        <?php if (!empty($error_message)) : ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <button type="submit" name="submit">Create Applicant</button>
    </form>
        </div>
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
    max-width: 600px;
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
    margin-bottom: 10px;
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

.error {
    color: red;
    margin-bottom: 10px;
}

</style>
</body>
</html>


