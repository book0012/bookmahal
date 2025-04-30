<?php
session_start();
include 'db_connect.php'; // ✅ डेटाबेस कनेक्शन

if ($_SERVER["REQUEST_METHOD"] == "POST") {}


    // ✅ अब नया यूजर रजिस्टर करें
    $email_otp = rand(100000, 999999);
    $mobile_otp = rand(100000, 999999);

    $query = "INSERT INTO users (name, email, email_otp, mobile, mobile_otp, password) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssss", $name, $email, $email_otp, $mobile, $mobile_otp, $password);
    
    if ($stmt->execute()) {
        $_SESSION['user_id'] = $stmt->insert_id;
        }
        // ✅ OTP भेजें (Mail/SMS API सेटअप करें)
        mail($email, "Your OTP Code", "Your OTP is: $email_otp");
        if ($stmt->execute()) {}
    // ✅ OTP Send to Email (PHP Mail)
    $subject = "Your OTP for BookMahal Verification";
    $message = "Your OTP is: $email_otp";
    $headers = "From: no-reply@bookmahal.com";
    mail($email, $subject, $message, $headers);

    // ✅ OTP Send to Mobile (SMS API - Example)
    // यहाँ पर अपनी SMS API का उपयोग करो
    // file_get_contents("https://smsapi.com/send?mobile=$mobile&message=Your+OTP+is+$mobile_otp");

    // ✅ Redirect to OTP Verification Page
    $_SESSION['email'] = $email;
    header("Location: verify_otp1.php");
    exit;

 else {
    echo "❌ Registration Failed!";
}
?>


        echo "<script>alert('✅ पंजीकरण सफल हुआ!'); window.location.href='verify_email1.php';</script>";
        exit;
    } else {
        echo "<script>alert('❌ रजिस्ट्रेशन फेल!');</script>";
    }
}
?>

<form method="POST">
    <input type="text" name="name" required placeholder="नाम">
    <input type="email" name="email" required placeholder="ईमेल">
    <input type="text" name="mobile" required placeholder="मोबाइल नंबर">
    <input type="password" name="password" required placeholder="पासवर्ड">
    <button type="submit">रजिस्टर करें</button>
</form>
