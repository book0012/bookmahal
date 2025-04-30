session_start();
include 'db_connect.php';

if (!isset($_SESSION['email'])) {
    die("❌ Invalid Access!");
}

$email = $_SESSION['email'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $otp_entered = $_POST['otp'];

    // 📌 Check OTP from Database
    $query = "SELECT email_otp FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user['email_otp'] == $otp_entered) {
        // ✅ Mark Email as Verified
        $update_query = "UPDATE users SET is_verified = 1 WHERE email = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        echo "<script>alert('✅ Verification Successful!'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('❌ Invalid OTP!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
</head>
<body>
    <h2>Enter OTP Sent to Your Email</h2>
    <form action="" method="POST">
        <input type="text" name="otp" placeholder="Enter OTP" required>
        <button type="submit">Verify</button>
    </form>
</body>
</html>
