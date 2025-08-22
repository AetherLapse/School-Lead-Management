<?php
require_once 'config.php';
$page_title = 'Bulk Upload';
include 'header.php';

session_start();


// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: Login.php"); // Redirect to login page if not logged in
    exit();
}

// Process form submission if it has been submitted
if (isset($_POST['submit'])) {
    // Access uploaded file
    $file = $_FILES['file'];

    // Validate file type
    $allowed_types = array('csv');
    $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    if (!in_array($file_extension, $allowed_types)) {
        die("Invalid file type. Only CSV files are allowed.");
    }

    // Validate file size (replace with your desired limit)
    if ($file['size'] > 2097152) { // 2MB limit
        die("File size exceeds 2MB limit.");
    }

    // Process CSV file
    if ($file['error'] == 0) {
        // Connect to database (replace with your credentials)
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Open CSV file
        $handle = fopen($file['tmp_name'], "r");
        $row = 0; // Skip header row
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if ($row > 0) { // Skip the first row (header)
                // Sanitize and validate data here
                foreach ($data as $key => $value) {
                    // Trim whitespace
                    $value = trim($value);
                
                    // Validate email
                    if ($key === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        die("Invalid email format in row $row");
                    }
                
                    // Validate phone number (using a regular expression or library)
                    if ($key === 'phone_number' && !preg_match('/^\d{10}$/', $value)) {
                        die("Invalid phone number format in row $row");
                    }
                    $dateSubmitted = date('Y-m-d');
                    // Sanitize other fields as needed (e.g., for full name, query)
                }
                

                // Prepare insert statement
                $stmt = $conn->prepare("INSERT INTO interested_applicants (full_name, email, phone_number, applying_for_class, query, date_submitted) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssss", $data[0], $data[1], $data[2], $data[3], $data[4], $dateSubmitted); // Adjust column indices based on your CSV format

                // Execute statement and handle success or failure
                if ($stmt->execute()) {
                    // Redirect to portal with success message after all rows are processed
                } else {
                    die("Error inserting data: " . $stmt->error);
                }
                $stmt->close();
            }
            $row++;
        }
        fclose($handle);

        // Close database connection
        $conn->close();

        header("location: index.php?bulk_uploaded=true"); // Redirect to portal with success message
    } else {
        die("Error uploading file: " . $file['error']);
    }
}

?>
<head>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container" style="margin: 10px; margin-top: 20px;">
    <form method="post" enctype="multipart/form-data">
        <label for="file">Select CSV File:</label>
        <input type="file" id="file" name="file" required>
        <br><br>

        <button type="submit" name="submit">Upload Data</button>
        <a href="uploads/example.csv" download>Download Example CSV File</a>
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
    max-width: 690px;
    margin: 0 auto;
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

input[type="file"] {
    width: 95%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 3px;
    font-size: 16px;
    margin-bottom: 10px;
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
