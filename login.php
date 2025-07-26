<?php
session_start();
include 'connection.php';

// Ensure the connection is established
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['admin_sign'])) {
    // Sanitize user input
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $sanitized_emailid = mysqli_real_escape_string($connection, $email);
    $sanitized_password = mysqli_real_escape_string($connection, $password);

    // Prepare the SQL statement to prevent SQL injection
    $sql = "SELECT * FROM admin WHERE email='$sanitized_emailid'";
    $result = mysqli_query($connection, $sql);

    // Check if the query was successful
    if ($result) {
        $num = mysqli_num_rows($result);
        if ($num == 1) {
            $row = mysqli_fetch_assoc($result);
            // Verify the password
            if (password_verify($sanitized_password, $row['password'])) {
                // Set session variables
                $_SESSION['admin_email'] = $sanitized_emailid; // Use sanitized email
                $_SESSION['admin_name'] = $row['name'];
                header("Location: admin_home.html"); // Redirect to admin home page
                exit(); // Always exit after a header redirect
            } else {
                echo "<h1><center>Login Failed: Incorrect password</center></h1>";
            }
        } else {
            echo "<h1><center>Account does not exist</center></h1>";
        }
    } else {
        echo "<h1><center>Error executing query: " . mysqli_error($connection) . "</center></h1>";
    }
}

// Close the database connection
mysqli_close($connection);
?>