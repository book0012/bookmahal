<?php
include 'db_connect.php'; // Database Connection

$language_filter = isset($_GET['language']) ? mysqli_real_escape_string($conn, $_GET['language']) : '';
$subject_filter = isset($_GET['subject']) ? mysqli_real_escape_string($conn, $_GET['subject']) : '';

$query = "SELECT * FROM books WHERE 1=1";

if ($language_filter) {
    $query .= " AND language = '$language_filter'";
}

if ($subject_filter) {
    $query .= " AND subject = '$subject_filter'";
}

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    while ($book = mysqli_fetch_assoc($result)) {
        echo "<div class='book-card'>";
         echo "<img src='" . htmlspecialchars($book['cover']) . "' alt='Book Cover'>";
        echo "</a>";
        echo "<h3>" . htmlspecialchars($book['title']) . "</h3>";
        echo "<p><strong>Author:</strong> " . htmlspecialchars($book['author']) . "</p>";
        echo "<p><strong>Language:</strong> " . htmlspecialchars($book['language']) . "</p>";
        echo "<p><strong>Subject:</strong> " . htmlspecialchars($book['subject']) . "</p>";
        echo "<a href='view.php?id=" . $book['id'] . "' class='btn'>Read Now</a>";
        echo "</div>";

    }
} else {
    echo "<p>No books found matching the filters.</p>";
}
?>
