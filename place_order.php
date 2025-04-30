<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die("❌ कृपया पहले लॉगिन करें!");
}

if (!isset($_GET['book_id'])) {
    die("❌ बुक ID नहीं मिली!");
}

$book_id = intval($_GET['book_id']);
$user_id = $_SESSION['user_id'];


// बुक डिटेल लाओ
$book_query = mysqli_query($conn, "SELECT * FROM books WHERE id = '$book_id'");
$book = mysqli_fetch_assoc($book_query);

if (!$book) {
    die("❌ बुक नहीं मिली!");
}

// बेस चार्जेस
$book_price = $book['price'];
$platform_charge = 20;
$delivery_charge = 50; // आप इसे distance के अनुसार dynamic भी कर सकते हैं

$total_price = $book_price + $platform_charge + $delivery_charge;
?>

<!DOCTYPE html>
<html>
<head>
    <title>📦 बुक खरीदें - <?php echo htmlspecialchars($book['title']); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding-top: 70px;
            margin: 0;
        }
        .navbar {
            position: fixed;
            top: 0; left: 0;
            width: 100%;
            background-color: #2c3e50;
            color: white;
            padding: 15px 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            z-index: 999;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar a {
            color: white;
            margin-left: 20px;
            text-decoration: none;
            font-weight: bold;
        }
        .container {
            max-width: 600px; margin: auto; background: white; padding: 20px;
            border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 { text-align: center; }
        input, select {
            width: 100%; padding: 10px; margin: 8px 0; border: 1px solid #ccc;
            border-radius: 5px;
        }
        .btn {
            background: #3498db; color: white; border: none; padding: 12px;
            width: 100%; font-size: 16px; border-radius: 5px;
        }
        .summary {
            background: #f0f8ff; padding: 10px; margin-top: 15px;
        }
    </style>
</head>
<body>
    <!-- ✅ Fixed Header/Navbar -->
<div class="navbar">
    <div><strong>📚 BookMahal</strong></div>
    <div>
        <a href="home.php">Home</a>
        <a href="my_orders.php">My Orders</a>
        <a href="profile.php">Logout</a>
    </div>
</div>
    <div class="container">
        <h2>📚 <?php echo htmlspecialchars($book['title']); ?></h2>
        <p><strong>लेखक:</strong> <?php echo $book['author']; ?></p>
        <div class="summary">
            <p>📖 Book Price: ₹<?php echo $book_price; ?></p>
            <p>💼 Platform Charge: ₹<?php echo $platform_charge; ?></p>
            <p>🚚 Delivery Charge: ₹<?php echo $delivery_charge; ?></p>
            <hr>
            <p><strong>💳 Total Payable: ₹<?php echo $total_price; ?></strong></p>
        </div>

        <form action="order_process.php" method="POST">
            <input type="hidden" name="book_id" value="<?php echo $book_id; ?>">
            <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">

            <input type="text" name="full_name" placeholder="Full Name" required>
            <input type="text" name="mobile" placeholder="Mobile Number" required>
            <input type="text" name="house" placeholder="House Number / Flat No." required>
            <input type="text" name="pincode" placeholder="PIN Code" required>
            <input type="text" name="tehsil" placeholder="Tehsil" required>

            <select name="state" id="state" required>
                <option value="">-- Select State --</option>
                <option value="Rajasthan">Rajasthan</option>
                <option value="Madhya Pradesh">Madhya Pradesh</option>
                <option value="Maharashtra">Maharashtra</option>
                <!-- बाकी राज्य भी जोड़ सकते हैं -->
            </select>

            <select name="district" id="district" required>
                <option value="">-- Select District --</option>
                <!-- JavaScript से dynamic district लोड होंगे -->
            </select>

            <select name="payment" required>
                <option value="">-- Select Payment Method --</option>
                <option value="credit_card">Credit Card</option>
                <option value="debit_card">Debit Card</option>
                <option value="upi">UPI</option>
                <option value="cod">Cash on Delivery</option>
            </select>

            <button type="submit" class="btn">📦 Place Order</button>
        </form>
    </div>

    <script>
        const districtByState = {
            "Rajasthan": ["Jaipur", "Jodhpur", "Udaipur"],
            "Madhya Pradesh": ["Bhopal", "Indore", "Gwalior"],
            "Maharashtra": ["Mumbai", "Pune", "Nagpur"]
        };

        document.getElementById('state').addEventListener('change', function () {
            const state = this.value;
            const districtSelect = document.getElementById('district');
            districtSelect.innerHTML = '<option value="">-- Select District --</option>';

            if (districtByState[state]) {
                districtByState[state].forEach(function (district) {
                    const option = document.createElement('option');
                    option.value = district;
                    option.text = district;
                    districtSelect.appendChild(option);
                });
            }
        });

    </script>

</body>
</html>
