<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
include 'db_connect.php'; // Database connection

// Fetch user details
$query = "SELECT * FROM users WHERE id=?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

// Fetch user books
$booksQuery = "SELECT * FROM books WHERE user_id=?";
$stmt_books = mysqli_prepare($conn, $booksQuery);
mysqli_stmt_bind_param($stmt_books, "i", $user_id);
mysqli_stmt_execute($stmt_books);
$booksResult = mysqli_stmt_get_result($stmt_books);

// Fetch user media (videos & audios)
$mediaQuery = "SELECT * FROM media WHERE user_id=?";
$stmt_media = mysqli_prepare($conn, $mediaQuery);
mysqli_stmt_bind_param($stmt_media, "i", $user_id);
mysqli_stmt_execute($stmt_media);
$mediaResult = mysqli_stmt_get_result($stmt_media);

// Fetch reading history
$historyQuery = "SELECT books.* FROM history 
                 JOIN books ON history.book_id = books.id 
                 WHERE history.user_id = ? ORDER BY history.timestamp DESC";
$stmt_history = mysqli_prepare($conn, $historyQuery);
mysqli_stmt_bind_param($stmt_history, "i", $user_id);
mysqli_stmt_execute($stmt_history);
$historyResult = mysqli_stmt_get_result($stmt_history);

// Fetch offline saved books
$offlineQuery = "SELECT * FROM offline_books WHERE user_id=?";
$stmt_offline = mysqli_prepare($conn, $offlineQuery);
mysqli_stmt_bind_param($stmt_offline, "i", $user_id);
mysqli_stmt_execute($stmt_offline);
$offlineResult = mysqli_stmt_get_result($stmt_offline);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - BookMahal</title>
    <link rel="stylesheet" href="profile.css">
</head>
<body>
    <header class="header">
        <h1 class="logo">BookMahal</h1>
        <nav>
            <ul class="nav-links">
                <li><a href="home.php">Home</a></li>
                <li><a href="subject.php">Subjects</a></li>
                <li><a href="my_orders.php">My Orders</a></li>
                <li><a href="help.php">Help & Support</a></li>
                <li><a href="logout.php" class="btn">Logout</a></li>
            </ul>
        </nav>
    </header>
    
    <main>
       <aside class="sidebar">
    <h3>📜 Reading History</h3>
    <ul>
        <?php if (mysqli_num_rows($historyResult) > 0): ?>
            <?php while ($history = mysqli_fetch_assoc($historyResult)): ?>
                <li><a href="view.php?id=<?php echo $history['id']; ?>">
                    <?php echo htmlspecialchars($history['title']); ?>
                </a></li>
            <?php endwhile; ?>
        <?php else: ?>
            <li>No history found.</li>
        <?php endif; ?>
    </ul>
    <a href="history.php" class="btn">View All</a>  <!-- History Page Button -->

    <h3>📚 Offline Saved Books</h3>
    <ul>
        <?php if (mysqli_num_rows($offlineResult) > 0): ?>
            <?php while ($offline = mysqli_fetch_assoc($offlineResult)): ?>
                <li><a href="offline_view.php?id=<?php echo $offline['id']; ?>">
                    <?php echo htmlspecialchars($offline['title']); ?>
                </a></li>
            <?php endwhile; ?>
        <?php else: ?>
            <li>No offline books saved.</li>
        <?php endif; ?>
    </ul>
    <a href="offline_books.php" class="btn">View All</a>  <!-- Offline Books Page Button -->
</aside>

        <section class="profile-section">
            <h2>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h2>
            <img src="<?php echo htmlspecialchars($user['profile_pic'] ?? 'default-profile.png'); ?>" alt="Profile Picture" class="profile-pic">
            <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
            <form action="upload_profile_pic.php" method="POST" enctype="multipart/form-data">
                <input type="file" name="profile_pic" required>
                <button type="submit">Upload Profile Picture</button>
            </form>
            <a href="edit_profile.php" class="btn">Edit Profile</a>
        </section>
        
        <section class="books-section">
            <h2>My Uploaded Books</h2>
            <?php if (mysqli_num_rows($booksResult) > 0): ?>
                <div class="books-grid">
                    <?php while ($book = mysqli_fetch_assoc($booksResult)): ?>
                        <div class='book-card'>
                            <h3><?php echo htmlspecialchars($book['title']); ?></h3>
                            <p>Author: <?php echo htmlspecialchars($book['author']); ?></p>
                            <a href="view.php?id=<?php echo $book['id']; ?>" class="btn">View</a>
                            <a href="edit_book.php?id=<?php echo $book['id']; ?>" class="btn">✏ Edit</a>

                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p>No books uploaded yet.</p>
            <?php endif; ?>
        </section>

        <section class="media-section">
            <h2>My Uploaded Videos & Audios</h2>
            <?php if (mysqli_num_rows($mediaResult) > 0): ?>
                <div class="media-grid">
                    <?php while ($media = mysqli_fetch_assoc($mediaResult)): ?>
                        <div class='media-card'>
                            <?php if ($media['type'] == 'video'): ?>
                                <video width="100%" controls>
                                    <source src="uploads/<?php echo $media['file_name']; ?>" type="video/mp4">
                                </video>
                            <?php else: ?>
                                <audio controls>
                                    <source src="uploads/<?php echo $media['file_name']; ?>" type="audio/mpeg">
                                </audio>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p>No videos or audios uploaded yet.</p>
            <?php endif; ?>
        </section>

        <section class="suggest-section">
            <h2>Suggest a Book</h2>
            <form action="suggest_book.php" method="POST">
                <input type="text" name="book_title" placeholder="Enter Book Title" required>
                <input type="text" name="author" placeholder="Enter Author Name" required>
                <textarea name="reason" placeholder="Why do you suggest this book?" required></textarea>
                <button type="submit">Submit Suggestion</button>
            </form>
        </section>
    </main>
</body>
</html>
