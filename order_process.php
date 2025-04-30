<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die("❌ कृपया पहले लॉगिन करें!");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $book_id = intval($_POST['book_id']);
    $total_price = floatval($_POST['total_price']);

    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $house = mysqli_real_escape_string($conn, $_POST['house']);
    $pincode = mysqli_real_escape_string($conn, $_POST['pincode']);
    $tehsil = mysqli_real_escape_string($conn, $_POST['tehsil']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $district = mysqli_real_escape_string($conn, $_POST['district']);
    $payment = mysqli_real_escape_string($conn, $_POST['payment']);

    // ✅ Check if already purchased
    $check = mysqli_query($conn, "SELECT * FROM purchases WHERE user_id = '$user_id' AND book_id = '$book_id'");
    if (mysqli_num_rows($check) > 0) {
        echo "<script>
            alert('⚠️ आप पहले ही यह बुक खरीद चुके हैं!');
            window.location.href='my_orders.php';
        </script>";
        exit;
    }

    // ✅ Insert Purchase
    $query = "INSERT INTO purchases 
        (user_id, book_id, purchase_date, full_name, mobile, house, pincode, tehsil, state, district, payment_method, total_price)
        VALUES 
        ('$user_id', '$book_id', NOW(), '$full_name', '$mobile', '$house', '$pincode', '$tehsil', '$state', '$district', '$payment', '$total_price')";

    if (mysqli_query($conn, $query)) {
        echo "<script>
            alert('✅ बुक सफलतापूर्वक खरीदी गई!');
            window.location.href='my_orders.php';
        </script>";
    } else {
        echo "❌ खरीदारी में समस्या आई: " . mysqli_error($conn);
    }
} else {
    echo "❌ Invalid Request!";
}
?>
