<?php
session_start();
include 'db_connect.php'; // Database Connection

$isLoggedIn = isset($_SESSION['user_id']);

// 📌 Check if book ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("❌ बुक ID नहीं मिली!");
}


$book_id = intval($_GET['id']);

// 📌 Fetch existing book details
$query = "SELECT * FROM books WHERE id = $book_id";
$result = mysqli_query($conn, $query);
$book = mysqli_fetch_assoc($result);

if (!$book) {
    die("❌ बुक डेटाबेस में नहीं मिली! ID: " . $book_id);
}

// 📌 If form is submitted, update the book
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $price = floatval($_POST['price']);

    // File Handling
    $cover = $book['cover']; // Existing Cover
    $pdf = $book['pdf']; // Existing PDF

    if (!empty($_FILES['cover']['name'])) {
        $cover = 'uploads/covers/' . time() . '_' . $_FILES['cover']['name'];
        move_uploaded_file($_FILES['cover']['tmp_name'], $cover);
    }

    if (!empty($_FILES['pdf']['name'])) {
        $pdf = 'uploads/pdfs/' . time() . '_' . $_FILES['pdf']['name'];
        move_uploaded_file($_FILES['pdf']['tmp_name'], $pdf);
    }

    // 📌 Update Query
    $update_query = "UPDATE books SET title='$title', author='$author', price='$price', cover='$cover', pdf='$pdf' WHERE id=$book_id";
    if (mysqli_query($conn, $update_query)) {
        echo "<script>alert('✅ बुक अपडेट हो गई!'); window.location='profile.php';</script>";
    } else {
        echo "<script>alert('❌ बुक अपडेट नहीं हुई!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book - <?php echo htmlspecialchars($book['title']); ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        form { width: 400px; margin: auto; background: #f4f4f4; padding: 20px; border-radius: 5px; }
        input, button { width: 100%; padding: 10px; margin: 5px 0; }
        img { width: 100px; height: 150px; object-fit: cover; display: block; margin: 10px 0; }
    </style>
</head>
<body>
    <h2>📖 Edit Book</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <label>Title:</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($book['title']); ?>" required>

        <label>Author:</label>
        <input type="text" name="author" value="<?php echo htmlspecialchars($book['author']); ?>" required>

        <label>Price (₹):</label>
        <input type="number" name="price" value="<?php echo htmlspecialchars($book['price']); ?>" required>

        <label>Cover Image:</label>
        <img src="<?php echo $book['cover']; ?>" alt="Current Cover">
        <input type="file" name="cover">

        <label>PDF File:</label>
        <input type="file" name="pdf">

        <button type="submit">💾 Update Book</button>
    </form>
</body>
</html>
