<?php
session_start();
include 'db_connect.php'; // Database Connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Check if user exists
    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: profile.php"); // Profile Page पर Redirect
        exit();
    } else {
        $_SESSION['message'] = "Invalid email or password!";
        header("Location: login.html"); // वापस लॉगिन पेज पर भेजें
        exit();
    }
}
?>
