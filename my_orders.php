<?php
session_start();
include 'db_connect.php';

$user_id = $_SESSION['user_id'] ?? 0;
if ($user_id == 0) {
    die("❌ कृपया पहले लॉगिन करें!");
}

// User के orders निकालना
$query = "SELECT p.*, b.title, b.cover, b.price, b.pdf FROM purchases p 
          JOIN books b ON p.book_id = b.id 
          WHERE p.user_id = '$user_id' ORDER BY p.purchase_date DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>📦 My Orders - BookMahal</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f4f4f4; }
        header {
            background-color: #2c3e50;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header h1 { margin: 0; font-size: 24px; }
        nav a {
            color: white;
            margin-left: 20px;
            text-decoration: none;
            font-weight: bold;
        }
        nav a:hover {
            text-decoration: underline;
        }

        .container {
            padding: 30px;
        }

        .order-card {
            background: white;
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            display: flex;
            gap: 20px;
            align-items: center;
            box-shadow: 0px 2px 6px rgba(0,0,0,0.1);
        }
        .cover-img {
            width: 100px;
            height: 140px;
            object-fit: cover;
            border-radius: 4px;
        }
        .order-details { flex: 1; }
        .status {
            font-weight: bold; color: green;
        }
        .download-btn {
            padding: 5px 10px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 8px;
        }
    </style>
</head>
<body>

<header>
    <h1>📚 BookMahal</h1>
    <nav>
        <a href="home.php">Home</a>
        <a href="my_orders.php">My Orders</a>
        <a href="profile.php">My Profile</a>
        <a href="login.html">Login</a>
    </nav>
</header>

<div class="container">
    <h2>📦 My Orders</h2>

    <?php while ($order = mysqli_fetch_assoc($result)): ?>
        <div class="order-card">
            <img src="<?php echo $order['cover']; ?>" class="cover-img" alt="Book Cover">
            <div class="order-details">
                <h3><?php echo htmlspecialchars($order['title']); ?></h3>
                <p>💰 Price: ₹<?php echo number_format($order['price'], 2); ?></p>
                <p>📅 Purchased On: <?php echo date('d M Y, h:i A', strtotime($order['purchase_date'])); ?></p>
                <p class="status">📦 Status: 
                    <?php echo $order['tracking_status'] ?? 'Ordered'; ?>
                </p>
                <?php if (!empty($order['pdf'])): ?>
                    <a href="<?php echo $order['pdf']; ?>" target="_blank" class="download-btn">📥 Download PDF</a>
                <?php else: ?>
                    <p>🚚 Delivery Status: <?php echo $order['delivery_status'] ?? 'Pending'; ?></p>
                <?php endif; ?>
            </div>
        </div>
    <?php endwhile; ?>
</div>

</body>
</html>
