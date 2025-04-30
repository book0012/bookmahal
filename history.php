<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

include 'db_connect.php'; // Database connection
$user_id = $_SESSION['user_id'];

// Fetch full history
$query = "SELECT books.* FROM history 
          JOIN books ON history.book_id = books.id 
          WHERE history.user_id = ? ORDER BY history.timestamp DESC";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reading History - BookMahal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>📜 Your Reading History</h2>
    <ul>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <li><a href="view.php?id=<?php echo $row['id']; ?>">
                <?php echo htmlspecialchars($row['title']); ?>
            </a></li>
        <?php endwhile; ?>
    </ul>
    <a href="profile.php" class="btn">Back to Profile</a>
</body>
</html>
