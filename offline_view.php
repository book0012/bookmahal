<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

include 'db_connect.php';

$user_id = intval($_SESSION['user_id']);
$book_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Check if the book belongs to the logged-in user
$query = "SELECT * FROM offline_books WHERE id=? AND user_id=?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ii", $book_id, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    echo "<h2>Book Not Found or Unauthorized Access!</h2>";
    exit();
}

$book = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($book['title']); ?> - Offline Book</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="header">
        <h1 class="logo">BookMahal</h1>
        <nav>
            <ul class="nav-links">
                <li><a href="home.php">Home</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="logout.php" class="btn">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="book-view">
            <h2><?php echo htmlspecialchars($book['title']); ?></h2>
            <p>Author: <?php echo htmlspecialchars($book['author']); ?></p>
            
            <!-- If book is a PDF -->
            <?php if (pathinfo($book['file_path'], PATHINFO_EXTENSION) === 'pdf'): ?>
                <embed src="offline_books/<?php echo htmlspecialchars($book['file_path']); ?>" type="application/pdf" width="100%" height="600px">
            <?php else: ?>
                <p>Download: <a href="offline_books/<?php echo htmlspecialchars($book['file_path']); ?>" download>Click Here</a></p>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
