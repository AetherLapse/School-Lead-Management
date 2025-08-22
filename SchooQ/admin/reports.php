<?php
session_start();
require 'vendor/autoload.php';
require_once 'config.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['status'], $_POST['start_date'], $_POST['end_date'])) {
    $status = $_POST['status'];

    $startDate = date('Y-m-d', strtotime($_POST['start_date']));
    $endDate = date('Y-m-d', strtotime($_POST['end_date']));

    if (!$startDate || !$endDate) {
        echo "Invalid date format. Please enter dates in YYYY-MM-DD format.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM interested_applicants WHERE status = ? AND date_submitted BETWEEN ? AND ?");
        $stmt->bind_param("sss", $status, $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $reportData = [];
            while ($row = $result->fetch_assoc()) {
                $reportData[] = $row;
            }
            $_SESSION['reportData'] = $reportData;
            echo "<div class='out'>
                Report Generated Successfully! Choose your preferred format and download.<br/><br/>
                <a class='csv' href='reports.php?download=csv'>Download CSV</a>
                <a class='xls' href='reports.php?download=xlsx'>Download XLSX</a>
            </div>";

            $stmt->close();
        } else {
            echo "No applicant data found within the given criteria.";
        }
    }
}

if (isset($_GET['download'])) {
    $format = $_GET['download'];
    $reportData = isset($_SESSION['reportData']) ? $_SESSION['reportData'] : null;
    if ($reportData) {
        if ($format == 'csv') {
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="report.csv"');

            $fp = fopen('php://output', 'w');

            fputcsv($fp, array_keys($reportData[0]));
            
            foreach ($reportData as $row) {
                fputcsv($fp, $row);
            }
            fclose($fp);
            exit();
        } elseif ($format == 'xlsx') {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $headerRow = 1;
            $col = 1;
            foreach ($reportData[0] as $key => $value) {
            $sheet->setCellValueByColumnAndRow($col, $headerRow, $key);
            $col++;
    }

            $row = 2;
            foreach ($reportData as $reportRow) {
                $col = 1;
                foreach ($reportRow as $cellValue) {
                    $sheet->setCellValueByColumnAndRow($col, $row, $cellValue);
                    $col++;
            }
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="report.xlsx"');
        $writer->save('php://output');
        exit();
    }
    unset($_SESSION['reportData']);
} else {
    echo "No applicant data found to download.";
}
}

$stmt = $conn->prepare("SELECT ID, A FROM master ORDER BY ID DESC");
$stmt->execute();
$result = $stmt->get_result();

$dropdownOptions = "";
while ($row = $result->fetch_assoc()) {
    $dropdownOptions .= "<option value='" . $row['A'] . "'>" . $row['A'] . "</option>";
}
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<style>
    select {
    width: 150px; /* Adjust as needed */
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
    background-position: right 5px center;
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

    .csv {
    padding: 5px 10px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-right: 10px;
    text-decoration: none;
    font-size: 20px;
    }

    .xls {
    padding: 5px 10px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-right: 10px;
    text-decoration: none;
    font-size: 20px;
    } 
    .out {
    padding: 10px;
    color: green;
    font-weight: bold;
    font-size: 20px;
    text-align: center;
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    margin: 300px auto;
    width: 500px; /* Or any desired width */
    height: 300px;

    }

        </style>
        <div class="container" style="margin: 10px; margin-top: 20px;">
<form method="POST">
    <h1>Download Reports</h1>
    <label for="start_date" style="display: inline-block;">From:</label>
    <input type="date" id="next_follow_up_date" name="start_date" style="display: inline-block;" required>
    <label for="end_date" style="display: inline-block;">To:</label>
    <input type="date" id="next_follow_up_date" name="end_date" style="display: inline-block;" required>
    <label for="status" style="display: inline-block;">Status:</label>
    <select placeholder="Select" style="display: inline-block;" name="status" required>
        <?php echo $dropdownOptions; ?>
    </select>
    <button type="submit" style="display: inline-block;">Generate</button>
</form>
</div>

</body>
</html>
