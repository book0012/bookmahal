<?php
session_start();
include 'db_connect.php'; // ✅ Database कनेक्शन

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 🛠️ पोस्ट डेटा चेक करें
    if (!isset($_POST['title']) || empty(trim($_POST['title']))) {
        die("❌ एरर: मीडिया टाइटल आवश्यक है!");
    }

    // 📌 फॉर्म से डेटा प्राप्त करें
    $book_id = intval($_POST['book_id']);
    $title = trim($_POST['title']);
    $part = isset($_POST['part']) ? intval($_POST['part']) : NULL;
    $media_type = $_POST['media_type'];

    // 🛠️ फाइल अपलोड करें
    $upload_dir = "uploads/" . ($media_type == "video" ? "videos/" : "audios/");
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $file_name = time() . "_" . basename($_FILES["media_file"]["name"]);
    $target_file = $upload_dir . $file_name;

    if (move_uploaded_file($_FILES["media_file"]["tmp_name"], $target_file)) {
        // ✅ डेटा DB में सेव करें
        $query = "INSERT INTO media (book_id, user_id, title, part, file_name, type) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iissss", $book_id, $_SESSION['user_id'], $title, $part, $file_name, $media_type);
        
        if ($stmt->execute()) {
            echo "✅ मीडिया सफलतापूर्वक अपलोड हो गया!";
            header("Location: view.php?id=$book_id"); // ✅ वापस बुक पेज पर जाएँ
            exit;
        } else {
            echo "❌ डेटा सेव करने में त्रुटि हुई!";
        }
    } else {
        echo "❌ फाइल अपलोड फेल!";
    }
}
?>
