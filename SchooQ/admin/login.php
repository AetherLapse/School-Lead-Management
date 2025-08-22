<?php
require_once 'config.php';
session_start(); // Start the session

// Connect to your database (replace placeholders with your credentials)
$conn = mysqli_connect($servername, $username, $password, $dbname);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs to prevent SQL injection
    $username = mysqli_real_escape_string($conn, $_POST["username"]);

    // Retrieve user information from the database using prepared statement
    $stmt = $conn->prepare("SELECT password FROM admin_users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Verify password
    if ($row && $_POST["password"] === $row['password']) {
        // Successful login
        $_SESSION['loggedin'] = true;
        header("location: index.php"); // Redirect to portal
        exit();
    } else {
        // Invalid credentials
        $error = "<div class='modal' align='center' style='display: block;'><div class='modal-content' align='center'>Invalid username or password<br/><a align='center' class='close'>Close</a></div></div>";
    }

    $stmt->close(); // Close the prepared statement
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Login</title>
</head>
<body>
    <style>
        body {
  background: #f5f5f5;
  font-family: 'Roboto', sans-serif;
}

form {
  width: 300px;
  margin: auto;
  padding: 40px;
  padding-top: 10px;
  border-radius: 15px;
  background: linear-gradient(to bottom, #f5f5f5, #e0e0e0);
  box-shadow: 7px 7px 28px #7f7f7f, -7px -7px 28px #ffffff;
  display: flex;
  flex-direction: column;
  align-items: center;
  position: absolute;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  height: max-content;
}

h1 {
  text-align: center;
  margin-bottom: 20px;
}

label, input, button {
  display: block;
  margin-bottom: 10px;
}

input[type="text"],
input[type="password"] {
  width: 100%;
  padding: 15px;
  border: 1px solid #007fff;
  border-radius: 25px;
  box-sizing: border-box;
}

input[type="text"]:hover,
input[type="password"]:hover {
  border: 1px solid cyan;
}

button[type="submit"] {
  background-color: #007fff;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 25px;
  cursor: pointer;
  transition: background-color 0.3s ease;
  font-size: 20px;
}

button[type="submit"]:hover {
  background-color: cyan;
}

.modal {
  display: none;
  z-index: 1;
  position: absolute;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  width: 400px;
  height: 200px;
  overflow: auto;
  margin: auto;
  background-color: rgba(255, 255, 255, 0.9);
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
  display: flex;
  flex-direction: column;
  align-items: center;
  }
  .modal-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: none;
  width: 80%;
  height: 50%;
  /*align content to center*/
  text-align: center;
  position: absolute;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  margin: auto;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  justify-content: center;
  align-items: center;

  }
  /* The Close Button */
  .close {
    font-size: 15px;
    font-weight: bold;
    padding: 10px;
    border-radius: 10px;
    background-color: #007fff;
    color: white;
    cursor: pointer;
    transition: background-color 0.3s ease;
    border: none;
    margin-top: 20px;
    }
    @media screen and (max-width: 600px) {
    .modal-content {
      
      width: 300px;
      }
      }
      .close:hover,
      .close:focus {
      background-color: cyan;
      cursor: pointer;
      }

p.error {
  color: red;
  margin-bottom: 10px;
  text-align: center;
}
        </style>
<?php if (isset($error)) { echo "<p>" . $error . "</p>"; } ?>
<form method="post">
<h2 align="center">Login to Continue</h2>
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" placeholder="Enter Username"><br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" placeholder="Enter Password"><br><br>
    <button type="submit">Login</button>
</form>
<script>
  /* Closes the modal using close button when after window loads */
  window.onload = function() {
    document.querySelector('.close').addEventListener('click', function() {
      document.querySelector('.modal').style.display = 'none';
    })
  }
  </script>
</body>
</html>
