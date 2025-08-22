<?php
require_once 'config.php';
$page_title = 'Dashboard';
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


if (isset($_POST['start_date']) && isset($_POST['end_date'])) {
    $startDate = date('Y-m-d', strtotime($_POST['start_date']));
    $endDate = date('Y-m-d', strtotime($_POST['end_date']));

    // Ensure valid dates were provided
    if (!$startDate || !$endDate) {
        echo "Invalid date format. Please enter dates in YYYY-MM-DD format.";
    } else {
        $sql = "SELECT a.*,
        (SELECT f.date_added
         FROM follow_up_remarks AS f
         WHERE f.applicant_id = a.id
         ORDER BY f.date_added DESC
         LIMIT 1) AS latest_follow_up_date,
        (SELECT f.follow_up_status
         FROM follow_up_remarks AS f
         WHERE f.applicant_id = a.id
         ORDER BY f.date_added DESC
         LIMIT 1) AS latest_follow_up_status
 FROM interested_applicants AS a
 WHERE a.date_submitted BETWEEN '$startDate' AND '$endDate'
 GROUP BY a.id
 ORDER BY a.id DESC;";
        $result = $conn->query($sql);

        if (!$result) {
            echo "Error: " . $conn->error;
        } else {
            // Display filtered data
            // ...
        }
    }
} else {
    $sql = "SELECT a.*,
    (SELECT f.date_added
     FROM follow_up_remarks AS f
     WHERE f.applicant_id = a.id
     ORDER BY f.date_added DESC
     LIMIT 1) AS latest_follow_up_date,
    (SELECT f.follow_up_status
     FROM follow_up_remarks AS f
     WHERE f.applicant_id = a.id
     ORDER BY f.date_added DESC
     LIMIT 1) AS latest_follow_up_status
FROM interested_applicants AS a
GROUP BY a.id
ORDER BY a.id DESC;";

    $result = $conn->query($sql);
}

function fetchMasterNameById($masterId) {
    global $conn;
  
    // Prepare the SQL query to fetch the Master Name based on the given ID
    $sql = "SELECT a FROM master WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $masterId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
  
    if (mysqli_num_rows($result) === 1) {  // Ensure only one row is returned
        $row = mysqli_fetch_assoc($result);
        $masterName = $row['a'];
        mysqli_stmt_close($stmt);
        return $masterName;
    } else {
        mysqli_stmt_close($stmt);
        return null;  // Handle the case where no matching Master Name is found
    }
  }


?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>
    <div class="container" style="margin: 10px; margin-top: 20px;">
    <h2>Filter by Date</h2>
    <form method="post">
        <label for="start_date" style="display: inline-block;">Start Date:</label>
        <input type="date" id="start_date" name="start_date" style="display: inline-block;" required>
        <label for="end_date" style="display: inline-block;">End Date:</label>
        <input type="date" id="end_date" name="end_date" style="display: inline-block;" required>
        <button type="submit" style="display: inline-block;">Filter</button>
    </form>

    <table border="1">
        <thead>
            <tr>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>For Class</th>
                <th>Query</th>
                <th>Date Created</th>
                <th>Last Follow up Date</th>
                <th>Status</th>
                <th>Edit/Delete</th>
                <th>Follow Ups</th>
                </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['full_name'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td>" . $row['phone_number'] . "</td>";
                    echo "<td>" . $row['applying_for_class'] . "</td>";
                    echo "<td>" . $row['query'] . "</td>";
                    echo "<td>" . $row['date_submitted'] . "</td>";
                    echo "<td>" . $row['latest_follow_up_date'] . "</td>";
                    $latestFollowUpStatusName = $row['latest_follow_up_status'];
                    $masterName = fetchMasterNameById($latestFollowUpStatusName);
                    echo "<td>" . $masterName . "</td>";
                    echo "<td><a style='text-decoration: none; display: inline-block;
                    padding: 8px 12px;
                    border: none;
                    border-radius: 5px;
                    text-decoration: none;
                    font-size: 14px;
                    margin-right: 5px;
                    background-color: #007fff;
                    color: white;' class='edit' href='edit_applicant.php?id=" . $row['id'] . "'>Edit</a> <a class='delete' style='text-decoration: none; display: inline-block;
                    padding: 8px 12px;
                    border: none;
                    border-radius: 5px;
                    text-decoration: none;
                    font-size: 14px;
                    margin-right: 5px;
                    background-color: red;
                    color: white;' href='delete_applicant.php?id=" . $row['id'] . "'>Delete</a></td>";
                    echo "<td><a href='follow_up.php?id=" . $row['id'] . "' class='follow-up-button'>Follow Up</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='10'>No data found</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <?php
    $conn->close();
    ?>
    </div>
</body>
</html>
