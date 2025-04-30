<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    die("Please login first!");
}

if (!isset($_POST['book_id'])) {
    die("Book ID not found!");
}

$book_id = intval($_POST['book_id']);
$user_id = $_SESSION['user_id'];

// Fetch Book Details
$query = "SELECT * FROM books WHERE id = '$book_id'";
$result = mysqli_query($conn, $query);
$book = mysqli_fetch_assoc($result);

if (!$book) {
    die("Book not found!");
}

$price = $book['price'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Purchase Book - <?php echo $book['title']; ?></title>
</head>
<body>

<h2>Buy Hard Copy</h2>
<p>Book: <?php echo $book['title']; ?></p>
<p>Author: <?php echo $book['author']; ?></p>
<p>Price: ₹<?php echo number_format($price, 2); ?></p>

<form action="order_process.php" method="POST">
    <input type="hidden" name="book_id" value="<?php echo $book_id; ?>">
    <textarea name="address" placeholder="Enter Shipping Address" required></textarea>
    <select name="payment" required>
        <option value="credit_card">Credit Card</option>
        <option value="debit_card">Debit Card</option>
        <option value="upi">UPI</option>
    </select>
    <button type="submit">Pay & Order</button>
</form>

</body>
</html>
