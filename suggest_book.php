<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

include 'db_connect.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $book_title = trim($_POST['book_title']);
    $author = trim($_POST['author']);
    $reason = trim($_POST['reason']);

    if (!empty($book_title) && !empty($author) && !empty($reason)) {
        $query = "INSERT INTO suggestions (user_id, book_title, author, reason) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "isss", $user_id, $book_title, $author, $reason);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            header("Location: profile.php?success=1"); // ✅ Auto-redirect to profile
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Please fill in all fields.";
    }
}
?>
