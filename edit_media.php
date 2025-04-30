<?php
session_start();
include 'db_connect.php'; // ✅ Database कनेक्शन

// 🔹 URL से मीडिया ID प्राप्त करें
$media_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($media_id == 0) {
    die("❌ मीडिया ID नहीं मिली!");
}

// 🔹 मीडिया डिटेल्स लाना
$query = "SELECT * FROM media WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $media_id);
$stmt->execute();
$result = $stmt->get_result();
$media = $result->fetch_assoc();
if (!$media) {
    die("❌ मीडिया डेटाबेस में नहीं मिली!");
}

// 🔹 अपडेट का प्रोसेस
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $title = trim($_POST['title']);
    $part = trim($_POST['part']);

    if (empty($title)) {
        echo "❌ टाइटल आवश्यक है!";
    } else {
        $update_query = "UPDATE media SET title = ?, part = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ssi", $title, $part, $media_id);
        if ($stmt->execute()) {
            echo "✅ मीडिया अपडेट हो गया!";
        } else {
            echo "❌ अपडेट फेल!";
        }
    }
}

// 🔹 मीडिया डिलीट प्रोसेस
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    // फाइल का पाथ
    $file_path = "uploads/" . ($media['type'] == 'video' ? "videos/" : "audios/") . $media['file_name'];

    // DB से हटाएँ
    $delete_query = "DELETE FROM media WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $media_id);
    
    if ($stmt->execute()) {
        // फाइल हटाएँ
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        echo "✅ मीडिया डिलीट हो गया!";
        header("Location: home.php");
        exit;
    } else {
        echo "❌ डिलीट करने में समस्या हुई!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Media</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>मीडिया एडिट करें</h2>
    <form action="" method="POST">
        <label>Title:</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($media['title']); ?>" required>
        
        <label>Part:</label>
        <input type="number" name="part" value="<?php echo htmlspecialchars($media['part']); ?>">
        
        <button type="submit" name="update">💾 Save</button>
        <button type="submit" name="delete" onclick="return confirm('क्या आप वाकई इस मीडिया को डिलीट करना चाहते हैं?')">🗑 Delete</button>
    </form>
</body>
</html>
