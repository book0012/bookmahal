<?php
include 'db_connect.php';

$seller_id = isset($_POST['seller_id']) ? intval($_POST['seller_id']) : 0;
$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;

if ($seller_id == 0 || $order_id == 0) {
    die("Invalid Request!");
}

// Check if order exists and is pending
$query = "SELECT * FROM orders WHERE id = $order_id AND status = 'pending'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    die("Order already accepted or doesn't exist.");
}

// Update order
$update = "UPDATE orders SET status = 'accepted', accepted_by = $seller_id WHERE id = $order_id";
if (mysqli_query($conn, $update)) {
    echo "✅ Order Accepted Successfully by Seller #$seller_id";
} else {
    echo "❌ Failed to accept order: " . mysqli_error($conn);
}
?>
