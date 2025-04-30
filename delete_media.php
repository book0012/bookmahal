<?php
include 'db_connect.php';

if (isset($_GET['id']) && isset($_GET['book_id'])) {
    $media_id = intval($_GET['id']);
    $book_id = intval($_GET['book_id']);

    // मीडिया की डिटेल्स लो
    $query = "SELECT file_url FROM media WHERE id = '$media_id'";
    $result = mysqli_query($conn, $query);
    $media = mysqli_fetch_assoc($result);

    if ($media) {
        // फाइल को सर्वर से हटाओ
        unlink($media['file_url']);

        // डेटाबेस से एंट्री हटाओ
        mysqli_query($conn, "DELETE FROM media WHERE id = '$media_id'");

        // बुक के पेज पर वापस भेज दो
        header("Location: view.php?id=$book_id");
        exit;
    } else {
        echo "❌ मीडिया नहीं मिला!";
    }
} else {
    echo "⚠️ Invalid Request!";
}
?>
