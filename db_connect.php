<?php
$servername = "localhost";  // XAMPP में localhost ही होता है
$username = "root";         // XAMPP में डिफ़ॉल्ट यूज़र root होता है
$password = "";             // XAMPP में डिफ़ॉल्ट पासवर्ड खाली होता है
$database = "book_mahal";   // आपके database का नाम

// Create Connection
$conn = new mysqli($servername, $username, $password, $database);

// Check Connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: UTF-8 Support
mysqli_set_charset($conn, "utf8mb4");
?>
