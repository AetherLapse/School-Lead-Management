<?php
require_once 'config.php';
$page_title = 'Follow Up with Applicant';
include 'header.php';
session_start();


if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("location: Login.php"); // Redirect to login page if not logged in
  exit();
}
// Retrieve applicant ID from GET parameter
$applicant_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

// Connect to database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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


// Fetch applicant data
$sql = "SELECT * FROM interested_applicants WHERE id = '$applicant_id'";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    $applicant = $result->fetch_assoc();

    // Fetch existing remarks
    $sql_remarks = "SELECT * FROM follow_up_remarks WHERE applicant_id = '$applicant_id' ORDER BY date_added DESC";
    $remarks_result = $conn->query($sql_remarks);
}

$masterTableSql = "SELECT ID, A FROM master ORDER BY ID DESC";
$masterTableResult = mysqli_query($conn, $masterTableSql);

    
// Create the dropdown options
$dropdownOptions = "";
while ($masterTableRow = mysqli_fetch_assoc($masterTableResult)) {
  $dropdownOptions .= "<option value='" . $masterTableRow['ID'] . "'>" . $masterTableRow['A'] . "</option>";
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $remarks = filter_var($_POST['remarks'], FILTER_SANITIZE_STRING);
  $next_follow_up_date = date('Y-m-d', strtotime($_POST['next_follow_up_date']));
  $follow_up_status = filter_var($_POST['follow_up_status'], FILTER_SANITIZE_STRING);

  $stmt = $conn->prepare("INSERT INTO follow_up_remarks (applicant_id, remarks, next_follow_up_date, follow_up_status) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("isss", $applicant_id, $remarks, $next_follow_up_date, $follow_up_status);

    if ($stmt->execute()) {
        header("Location: follow_up.php?id=$applicant_id&success=1");
        exit();
    } else {
      die("Error updating applicant status: " . $stmt->error);
    }
}
?>

<style>
  body {
  font-family: sans-serif;
  background-color: #f5f5f5;
  }


  table {
  border-collapse: collapse;
  width: 100%;
  }

  th, td {
  padding: 8px;
  text-align: left;
  border: 1px solid #ddd;
  }

  /* Highlight important information */
  .important {
  font-weight: bold;
  color: #007bff;
  }

  /* Styling for remarks */
  .remark {
  margin-bottom: 15px;
  border: 1px solid #eee;
  padding: 15px;
  }

  .remark .date {
  font-size: 0.8em;
  color: #999;
  }

  /* Form styling */
  textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  box-sizing: border-box; /* Include padding in width */
  }

  button {
  background-color: #007bff;
  color: white;
  padding: 10px 15px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  margin-top: 10px;
  }

  .back {
    color: white;
    float: right;
    background-color: red;
    padding: 10px;
    text-decoration: none;
    border-radius: 5px;
  }

  /* General form styling */
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
  display: block; /* Each label on a separate line */
  margin-bottom: 5px;
  font-weight: bold;
  }

  input, textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
  margin-bottom: 10px;
  box-sizing: border-box;
  }

  textarea {
  resize: vertical; /* Allow resizing only vertically */
  }

  /* Specific styling for new fields */
  #next_follow_up_date {
  /* Example: Add a calendar icon for clarity */
  background-image: url("calendar.svg"); /* Replace with your icon path */
  background-position: right 10px center;
  background-repeat: no-repeat;
  padding-right: 30px; /* Adjust for icon size */
  }

  #follow_up_status {
  /* Example: Add subtle hints for valid values */
  background-image: linear-gradient(to right, #f0f0f0 50%, #fff 50%);
  background-size: 100% 1.2em;
  background-position: bottom;
  background-repeat: no-repeat;
  }

  button {
  /* Visually align button with input fields */
  width: 100%;
  margin-top: 15px; /* Add some spacing before the button */
  }

  .remarks-section {
  float: left;
  width: 50vw;
  padding: 20px;
  border: 1px solid #ddd;
  border-radius: 5px;
  margin-top: 20px
  }

  .form-section {
  float: right;
  width: auto;
  padding: 20px;
  border: 1px solid #ddd;
  border-radius: 5px;
  margin-top: 20px
  }

  /* Base styling for all select fields */
  select {
  width: 100%; /* Adjust as needed */
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
  box-sizing: border-box;
  font-family: inherit; /* Inherit font from parent element */
  font-size: 14px; /* Adjust as needed */
  -webkit-appearance: none; /* Remove default arrow on some browsers */
  -moz-appearance: none;
  appearance: none;
  margin-bottom: 10px;
  }

  /* Placeholder styling */
  select::placeholder {
  color: #999; /* Adjust placeholder color */
  }

  /* Arrow customization */
  select::-ms-expand {
  display: none; /* Hide arrow on Internet Explorer */
  }

  /* Custom arrow (optional) */
  select {
  background-image: url("select.svg"); /* Replace with your arrow image */
  background-repeat: no-repeat;
  background-size: 30px;
  background-position: right 20px center;
  }

  /* Hover effect (optional) */
  select:hover {
  border-color: #007bff; /* Highlight on hover */
  }

  /* Focus effect */
  select:focus {
  outline: none; /* Remove default outline */
  border-color: #007bff; /* Highlight on focus */
  box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); /* Add a subtle glow */
  }

</style>
<link rel="stylesheet" href="css/style.css">
<div class="container" style="margin: 10px; margin-top: 20px;">

<h2>Applicant Information</h2>
<table>
    <tr><td>ID: </td><td><?php echo $applicant['id']; ?></td></tr>
    <tr><td>Name:</td><td><?php echo $applicant['full_name']; ?></td></tr>
</table>

<div class="remarks-section">
<h2>Existing Remarks</h2>

<table border=1>
    <thead>
        <tr>
            <th>Remark</th>
            <th>Date Added</th>
            <th>Next Follow Up Date</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
            if ($remarks_result && $remarks_result->num_rows > 0) {
                while ($row = $remarks_result->fetch_assoc()) {
                    $followUpStatusName = fetchMasterNameById($row['follow_up_status']);
                    echo "<tr>";
                    echo "<td>" . $row['remarks'] . "</td>";
                    echo "<td>" . $row['date_added'] . "</td>";
                    echo "<td>" . $row['next_follow_up_date'] . "</td>";
                    echo "<td>" . $followUpStatusName . "</td>";
                    echo "</tr>";
                    ?>
<?php
    }
} else {
?>
<p><td colspan="4">No remarks added yet.</td></p>
<?php
}
?>
</tbody>
</table>
</div>

<div class="form-section">
<h2 align="center">Add Remark</h2>
<form action="follow_up.php?id=<?php echo $applicant_id; ?>" method="POST">
    <label for="next_follow_up_date">Next Follow-Up Date:</label>
    <input type="date" id="next_follow_up_date" name="next_follow_up_date" required>
    <label for="follow_up_status">Follow-Up Status:</label>
    <select placeholder="Select" name="follow_up_status" required>
        <?php echo $dropdownOptions; ?>
    </select>
    <label for="remarks">Remarks</label>
    <textarea placeholder="Remarks to add" name="remarks" rows="5" cols="40" required></textarea>
    <button type="submit">Add Remark</button>
</form>
</div>
</div>
