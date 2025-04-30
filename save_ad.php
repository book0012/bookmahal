<?php
include 'db_connect.php'; // Database Connection
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id']; // जो यूजर लॉग इन है
    $ad_type = $_POST['ad_type'];
    $ad_title = $_POST['ad_title'];
    $ad_link = $_POST['ad_link'];
    $ad_image = $_FILES['ad_image']['name'];

    $target_dir = "ads/";
    $target_file = $target_dir . basename($_FILES["ad_image"]["name"]);
    move_uploaded_file($_FILES["ad_image"]["tmp_name"], $target_file);

    $query = "INSERT INTO ads (user_id, ad_type, title, link, image, status, views, clicks) VALUES ('$user_id', '$ad_type', '$ad_title', '$ad_link', '$ad_image', 'Pending', 0, 0)";
    
    if (mysqli_query($conn, $query)) {
        echo "✅ आपका ऐड सफलतापूर्वक सबमिट किया गया!";
    } else {
        echo "❌ ऐड सेव करने में दिक्कत: " . mysqli_error($conn);
    }
}
?>
