<?php
include 'db_connect.php'; // Database Connection

// Dummy User ID (इसे आपके लॉगिन सिस्टम के अनुसार सेट करें)
$user_id = 1;

// Fetch User's Reading History
$query = "SELECT books.title, books.author, books.cover_image, history.read_at 
          FROM reading_history AS history
          JOIN books ON history.book_id = books.id
          WHERE history.user_id = $user_id
          ORDER BY history.read_at DESC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reading History - BookMahal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .header {
            position: fixed;
            top: 0;
            width: 100%;
            background: #007bff;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
        }
        .container {
            width: 80%;
            margin: 70px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .book {
            display: flex;
            align-items: center;
            border-bottom: 1px solid #ddd;
            padding: 15px 0;
        }
        .book img {
            width: 80px;
            height: 100px;
            margin-right: 15px;
            border-radius: 5px;
        }
        .book-details {
            flex-grow: 1;
        }
        .book-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        .book-author {
            font-size: 14px;
            color: #666;
        }
        .read-date {
            font-size: 12px;
            color: #888;
        }
    </style>
</head>
<body>

    <div class="header"><a href="home.php">📖</a>Reading History</div>
        
    <div class="container">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="book">
                    <img src="uploads/<?php echo $row['cover_image']; ?>" alt="Book Cover">
                    <div class="book-details">
                        <div class="book-title"><?php echo $row['title']; ?></div>
                        <div class="book-author">by <?php echo $row['author']; ?></div>
                        <div class="read-date">Read on: <?php echo date("d M, Y", strtotime($row['read_at'])); ?></div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No reading history found.</p>
        <?php endif; ?>
    </div>

</body>
</html>
