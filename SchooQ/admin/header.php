<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - <?php echo $page_title; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav>
        <div class="header-container"><h1><?php echo $page_title;?></h1></div>
            <ul id="list">
                <li><a href="index.php" <?php if ($page_title=="Dashboard") { echo "class='active'";} ?>>Dashboard</a></li>
                <li><a href="create.php" <?php if ($page_title=="Create Applicant") { echo "class='active'";} ?>>Create Applicants</a></li>
                <li><a href="bulk.php" <?php if ($page_title=="Bulk Upload") { echo "class='active'";} ?>>Bulk Upload</a></li>
                <li><a href="master.php" <?php if ($page_title=="Master Data") { echo "class='active'";} ?>>Master Data</a></li>
                <li><a href="reports.php" <?php if ($page_title=="Reports") { echo "class='active'";} ?>>Reports</a></li>
                <li><a href="logout.php" class="logout">Logout</a></li>
            </ul>
        </nav>
    </header>

<style>
body {
    font-family: sans-serif;
    margin: 0;
}

header {
    border-bottom: 1px solid #ccc;
    margin: 5px;
    border-radius: 5px;
    padding-bottom: 10px;
    background-color: white;
    padding: 10px; 
}

nav {
    display: flex;
    justify-content: space-between; /* Align content to the left and right */
    align-items: center;
}

nav ul {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
}

nav li {
    display: inline-block;
    margin-right: 20px;
    float: right;
}

nav a {
    text-decoration: none;
    color: #333;
    padding: 5px 10px;
}


h1 {
    margin: 0 auto;
}


#list a:hover {
    transition: 0.5s;
    color: #007fff;

}

.active {
    color: #007fff;
    font-weight: bold;
}

.logout {
    color: red;
}

.logout:hover {
    transition: 0.5s;
    color: #bf0000 !important; 
}
</style>
