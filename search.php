<?php
include 'db_connect.php'; // Database Connection

$searchTerm = isset($_GET['query']) ? $_GET['query'] : '';

$query = "SELECT * FROM books WHERE title LIKE '%$searchTerm%' OR author LIKE '%$searchTerm%'";
$result = mysqli_query($conn, $query);

if (isset($_GET['ajax'])) {
    $books = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $row['cover_image'] = !empty($row['cover_image']) ? "uploads/" . $row['cover_image'] : "uploads/default_cover.jpg";
        $books[] = $row;
    }
    echo json_encode($books);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Books - BookMahal</title>
    <link rel="stylesheet" href="search.css">
</head>
<body>

<header class="header">
    <a href="home.php">🏠 Home</a>
    <h1>BookMahal - Search</h1>
    <input type="text" id="searchBox" placeholder="Search books..." onkeyup="fetchResults()">
</header>

<div id="results">
    <?php while ($row = mysqli_fetch_assoc($result)) { 
        $coverImage = !empty($row['cover_image']) ? "uploads/" . $row['cover_image'] : "uploads/default_cover.jpg";
        ?>
        <div class="book">
            <img src="<?php echo $coverImage; ?>" alt="Book Cover">
            <h3><?php echo $row['title']; ?></h3>
            <p>By <?php echo $row['author']; ?></p>
            <a href="view.php?id=<?php echo $row['id']; ?>" class="read-btn">📖 Read</a>
        </div>
    <?php } ?>
</div>

<script>
function fetchResults() {
    let query = document.getElementById('searchBox').value;
    let xhr = new XMLHttpRequest();
    xhr.open("GET", "search.php?query=" + query + "&ajax=true", true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            let books = JSON.parse(xhr.responseText);
            let resultsContainer = document.getElementById("results");
            resultsContainer.innerHTML = "";

            books.forEach(book => {
                let bookDiv = document.createElement("div");
                bookDiv.classList.add("book");

                let img = document.createElement("img");
                img.src = book.cover_image;
                img.alt = "Book Cover";

                let title = document.createElement("h3");
                title.textContent = book.title;

                let author = document.createElement("p");
                author.textContent = "By " + book.author;

                let readLink = document.createElement("a");
                readLink.href = "view.php?id=" + book.id;
                readLink.textContent = "📖 Read";
                readLink.classList.add("read-btn");

                bookDiv.appendChild(img);
                bookDiv.appendChild(title);
                bookDiv.appendChild(author);
                bookDiv.appendChild(readLink);

                resultsContainer.appendChild(bookDiv);
            });
        }
    };
    xhr.send();
}
</script>

</body>
</html>
