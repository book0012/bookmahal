<?php
include 'db_connect.php'; // डेटाबेस कनेक्शन फ़ाइल

// 🔹 Fetch Biographic Books
$query = "SELECT title, author, cover FROM books WHERE subject = 'biography'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biographic Books - Book Mahal</title>
    <link rel="stylesheet" href="create_biography_page.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('images/ideal_persons_bg.jpg') no-repeat center center fixed;
            background-size: cover;
            color: white;
            text-align: center;
        }
        .biography-container {
            padding: 20px;
        }
        body {
    background: url('/book_mahal/images/ideal_persons_bg.jpg') no-repeat center center fixed;
    background-size: cover;
}

        .books-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }
        .book-card {
            background: rgba(0, 0, 0, 0.7);
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            width: 200px;
        }
        .book-card img {
            width: 100%;
            height: 250px;
            border-radius: 5px;
        }
        .book-card h3, .book-card p {
            margin: 10px 0;
        }
        .book-card a {
            text-decoration: none;
            color: #FFD700;
        }
    </style>
</head>
<body>

<div class="biography-container">
    <h1>Biographic Books</h1>
        <a href="home.php">📖</a>
    <div class="books-gride">
    <?php while ($book = mysqli_fetch_assoc($result)) { ?>
      <div class="book-card">
         <a href="view.php?id=<?php echo $book['id']; ?>">
            <img src="<?php echo ($book['cover']); ?>" alt="book cover">
            <h3><?php echo $book['title']; ?></h3>
            <p>By <?php echo $book['author']; ?></p>
            </a>
        </div>
    <?php } ?>
</div>

</body>
</html>
