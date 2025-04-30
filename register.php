<?php
session_start();
include 'db_connect.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if user already exists
    $checkQuery = "SELECT * FROM users WHERE email='$email'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        // User already registered
        $_SESSION['message'] = "Already Registered! Please Login.";
        header("Location: login.html");
        exit();
    } else {
        // Register new user
        $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
        if (mysqli_query($conn, $query)) {
            $_SESSION['message'] = "Registration Successful! Please Login.";
            header("Location: login.html");
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>
