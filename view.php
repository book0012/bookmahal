<?php
session_start();
include 'db_connect.php'; // Database Connection

// 📌 Debugging: Check if 'id' is coming from URL
$book_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($book_id == 0) {
    die("❌ बुक ID नहीं मिली! URL में ?id=1 जैसा पास करें।");
}

// 🛠️ Fetch Book Details
$query = "SELECT * FROM books WHERE id = $book_id";
$result = mysqli_query($conn, $query);
$book = mysqli_fetch_assoc($result);
if (!$book) {
    die("❌ बुक डेटाबेस में नहीं मिली! ID: " . $book_id);
}
if ($user_id) {
    $purchase_check = mysqli_query($conn, "SELECT * FROM purchases WHERE user_id = '$user_id' AND book_id = '$book_id'");
    if (mysqli_num_rows($purchase_check) > 0) {
        $purchased = true;
    }
}

// 🛠️ Fetch Media Data
$video_query = "SELECT m.*, u.profile_pic, u.username FROM media m JOIN users u ON m.user_id = u.id WHERE book_id = '$book_id' AND type='video' ORDER BY likes DESC";
$video_result = mysqli_query($conn, $video_query);
$audio_query = "SELECT m.*, u.profile_pic, u.username FROM media m JOIN users u ON m.user_id = u.id WHERE book_id = '$book_id' AND type='audio' ORDER BY likes DESC";
$audio_result = mysqli_query($conn, $audio_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Book - <?php echo htmlspecialchars($book['title']); ?></title>
   <link rel="stylesheet" type="text/css" href="view.css">

    <style>
        .media-container {
            display: flex;
            justify-content: space-between;
        }
        .video-section, .audio-section {
            width: 48%;
        }
    </style>
    <style>
        .media-profile {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>

</head>
<body>
<header class="fixed-header">
    <h1>BookMahal</h1>
    <nav>
        <a href="home.php">Home</a>
        <a href="subject.php">Subjects</a>
        <a href="help.php">Help</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>
<main>
    <div class="content">
        <img src="<?php echo $book['cover']; ?>" alt="Book Cover" class="book-cover">
        <div>
            <h2><?php echo htmlspecialchars($book['title']); ?></h2>
            <p><strong>Author:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
            <button class="read-btn" onclick="window.open('<?php echo $book['pdf']; ?>', '_blank')">📖 Read</button>
           
             <p><strong>Price:</strong> ₹<?php echo number_format($book['price'], 2); ?></p>
               <button class="place_order.php?book_id=<?php echo $book['id']; ?>"
    💳 Purchase
</button>
<a href="place_order.php?book_id=<?php echo $book['id']; ?>">💳 Order Now</a>

     <button class="save-offline-btn"
                data-id="<?php echo $book['id']; ?>"
                data-title="<?php echo htmlspecialchars($book['title']); ?>"
                data-author="<?php echo htmlspecialchars($book['author']); ?>"
                data-cover="<?php echo htmlspecialchars($book['cover']); ?>"
                data-pdf="<?php echo htmlspecialchars($book['pdf']); ?>">
                📥 Save Offline
            </button>
        </div>
    </div>

    
    <h3>Upload Video/Audio</h3>
    <form action="upload_media.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="book_id" value="<?php echo $book_id; ?>">
        <input type="text" name="title" placeholder="Enter Media Title" required>
        <input type="number" name="part" placeholder="Enter Part Number (Optional)">
        <input type="file" name="media_file" required>
        <select name="media_type">
            <option value="">Select Media Type</option>
            <option value="video">Video</option>
            <option value="audio">Audio</option>
        </select>
        <button type="submit" class="btn">Upload</button>
    </form>
    
    <div class="media-container">
        <div class="video-section">
            <h3>Top Liked Videos</h3>
            <?php while ($video = mysqli_fetch_assoc($video_result)): ?>
                <div class="media-item">
                    <img src="<?php echo !empty($video['profile_pic']) ? htmlspecialchars($video['profile_pic']) : 'profile_pics/default.jpg'; ?>" alt="Profile Picture" class="media-profile small-profile">
                   <p><strong><?php echo htmlspecialchars($video['username']); ?></strong> 
   <?php echo isset($video['part']) ? '- Part ' . htmlspecialchars($video['part']) : ''; ?>
</p>

                   <button class="btn play-btn" onclick="window.location.href='play_media.php?file=<?php echo urlencode($video['file_name']); ?>'">▶ Play</button>

                    <a href="edit_media.php?id=<?php echo $video['id']; ?>" class="btn edit-btn">✏ Edit</a>
                </div>
            <?php endwhile; ?>
        </div>
        
        <div class="audio-section">
            <h3>Top Liked Audios</h3>
            <?php while ($audio = mysqli_fetch_assoc($audio_result)): ?>
                <div class="media-item">
                    <img src="<?php echo !empty($audio['profile_pic']) ? htmlspecialchars($audio['profile_pic']) : 'profile_pics/default.jpg'; ?>" alt="Profile Picture" class="media-profile small-profile">
                  <p><strong><?php echo htmlspecialchars($audio['username']); ?></strong> 
   <?php echo isset($audio['part']) ? '- Part ' . htmlspecialchars($audio['part']) : ''; ?>
</p>

                    <button class="btn play-btn" onclick="window.location.href='play_media.php?file=<?php echo urlencode($audio['file_name']); ?>'">▶ Play</button>

                    <a href="edit_media.php?id=<?php echo $audio['id']; ?>" class="btn edit-btn">✏ Edit</a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</main>
<script>
document.querySelectorAll('.play-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        window.open(this.getAttribute('data-media'), '_blank');
    });
});
</script>
</body>
</html>
<script>
function purchaseBook(bookId) {
    if (confirm("क्या आप इस बुक की हार्ड कॉपी खरीदना चाहते हैं?")) {
        fetch('place_order.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'book_id=' + bookId
        }).then(response => response.text())
        .then(data => {
            alert(data);
            location.reload();
        });
    }
}

function markAsRead(pdfUrl) {
    window.open(pdfUrl, '_blank');
}

document.querySelectorAll('.save-offline-btn').forEach(button => {
    button.addEventListener('click', function() {
        let book = {
            id: this.getAttribute('data-id'),
            title: this.getAttribute('data-title'),
            author: this.getAttribute('data-author'),
            cover: this.getAttribute('data-cover'),
            pdf: this.getAttribute('data-pdf')
        };

        let offlineBooks = JSON.parse(localStorage.getItem('offlineBooks')) || [];
        offlineBooks.push(book);
        localStorage.setItem('offlineBooks', JSON.stringify(offlineBooks));

        alert("✅ बुक ऑफ़लाइन सेव हो गई!");
    });
});
</script>

</body>
</html>