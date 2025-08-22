<?php
// Fill Credentials Here
$servername = ''; 
$username = '';
$password = '';
$dbname = '';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $fullName = $_POST['full_name'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phone_number'];
    $applyingForClass = $_POST['applying_for_class'];
    $dateSubmitted = $_POST['date_submitted'];
    $query = $_POST['query'];

    // Prepare the SQL query
    $sql = "INSERT INTO interested_applicants (full_name, email, phone_number, applying_for_class, query, date_submitted)
            VALUES (?, ?, ?, ?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind parameters to prevent SQL injection
    $stmt->bind_param("ssssss", $fullName, $email, $phoneNumber, $applyingForClass, $query, $dateSubmitted);

    // Execute the query
    if ($stmt->execute()) {
        echo "";
    } else {
        echo "Error inserting data: " . $conn->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thankyou for Submitting Data</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <meta http-equiv="refresh" content="5;URL='localhost:80/theaaryanstest/'" />
</head>
<body>
<style>
    @import url('https://fonts.googleapis.com/css?family=Raleway:100,200,300,400,500,600,700,800,900&display=swap');
html,body {
    font-family: 'Raleway', sans-serif;  
}
.thankyou-page ._header {
    background: #F2184F;
    padding: 100px 30px;
    text-align: center;
    background: #F2184F url(https://codexcourier.com/images/main_page.jpg) center/cover no-repeat;
}
.thankyou-page ._header .logo {
    max-width: 200px;
    margin: 0 auto 50px;
}
.thankyou-page ._header .logo img {
    width: 100%;
}
.thankyou-page ._header h1 {
    font-size: 65px;
    font-weight: 800;
    color: white;
    margin: 0;
}
.thankyou-page ._body {
    margin: -70px 0 30px;
}
.thankyou-page ._body ._box {
    margin: auto;
    max-width: 80%;
    padding: 50px;
    background: white;
    border-radius: 3px;
    box-shadow: 0 0 35px rgba(10, 10, 10,0.12);
    -moz-box-shadow: 0 0 35px rgba(10, 10, 10,0.12);
    -webkit-box-shadow: 0 0 35px rgba(10, 10, 10,0.12);
}
.thankyou-page ._body ._box h2 {
    font-size: 32px;
    font-weight: 600;
    color: black;
}
.thankyou-page ._footer {
    text-align: center;
    padding: 50px 30px;
}

.thankyou-page ._footer .btn {
    background: #F2184F;
    color: white;
    border: 0;
    font-size: 14px;
    font-weight: 600;
    border-radius: 0;
    letter-spacing: 0.8px;
    padding: 20px 33px;
    text-transform: uppercase;
}
</style>
<div class="thankyou-page">
    <div class="_header">
        <h1>Thank You!</h1>
    </div>
    <div class="_body">
        <div class="_box">
            <h2>
                <strong>Your Query has been sent</strong> we will contact you soon!.
            </h2>
        </div>
        <div class="_footer">
            <a class="btn">Redirecting back in 5 Seconds</a>
        </div>
    </div>
</div>
<script type="text/javascript">   
    function Redirect() 
    {  
        window.location="http://localhost/theaaryanstest/"; 
    } 
    setTimeout('Redirect()', 5000);   
</script>
</body>
</html>