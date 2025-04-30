<?php
session_start();

// 🔹 URL से मीडिया फ़ाइल का नाम प्राप्त करें
$file = isset($_GET['file']) ? $_GET['file'] : '';

if (empty($file)) {
    die("❌ कोई मीडिया फ़ाइल नहीं मिली!");
}

// 🔹 Ensure filename is safe
$file = basename($file);

// 🔹 फाइल एक्सटेंशन चेक करें
$file_extension = pathinfo($file, PATHINFO_EXTENSION);

// 🔹 मीडिया का सही पाथ सेट करें
if ($file_extension == "mp4") {
    $media_path = "uploads/videos/" . $file;
} elseif ($file_extension == "mp3") {
    $media_path = "uploads/audios/" . $file;
} else {
    die("❌ अनजान फ़ाइल फॉर्मेट!");
}

// 🔹 फाइल मौजूद है या नहीं, इसकी जाँच करें
if (!file_exists($media_path)) {
    die("❌ मीडिया फ़ाइल नहीं मिली! (Path: " . htmlspecialchars($media_path) . ")");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Play Media</title>
</head>
<body>
    <h2>Playing Media</h2>
    
    <?php if ($file_extension == "mp4"): ?>
        <video width="720" controls>
            <source src="<?php echo htmlspecialchars($media_path); ?>" type="video/mp4">
            ❌ आपका ब्राउज़र वीडियो सपोर्ट नहीं करता।
        </video>
    <?php elseif ($file_extension == "mp3"): ?>
        <audio controls>
            <source src="<?php echo htmlspecialchars($media_path); ?>" type="audio/mpeg">
            ❌ आपका ब्राउज़र ऑडियो सपोर्ट नहीं करता।
        </audio>
    <?php else: ?>
        <p>❌ अनजान फ़ाइल फॉर्मेट!</p>
    <?php endif; ?>

</body>
</html>
