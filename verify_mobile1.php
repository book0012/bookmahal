<?php
session_start();
include 'db_connect.php';

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_otp = $_POST['otp'];

    $query = "SELECT mobile_otp FROM users WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($entered_otp == $user['mobile_otp']) {
        $update_query = "UPDATE users SET mobile_verified=1 WHERE id=?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        header("Location: profile.php");
        exit;
    } else {
        echo "❌ गलत OTP!";
    }
}
?>

<form method="POST">
    <input type="text" name="otp" required placeholder="OTP दर्ज करें">
    <button type="submit">वेरिफाई करें</button>
</form>
