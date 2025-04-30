<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$query = "SELECT username, email, profile_pic FROM users WHERE id=?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Handle profile picture upload if file is selected
    if (!empty($_FILES['profile_pic']['name'])) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_name = basename($_FILES["profile_pic"]["name"]);
        $target_file = $target_dir . $file_name;
        
        if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
            // Update profile picture in DB
            $updatePicQuery = "UPDATE users SET profile_pic=? WHERE id=?";
            $stmtPic = mysqli_prepare($conn, $updatePicQuery);
            mysqli_stmt_bind_param($stmtPic, "si", $target_file, $user_id);
            mysqli_stmt_execute($stmtPic);
        } else {
            $message = "Profile picture upload failed!";
        }
    }
    
    // Update username and email
    $updateQuery = "UPDATE users SET username=?, email=? WHERE id=?";
    $stmtUpdate = mysqli_prepare($conn, $updateQuery);
    mysqli_stmt_bind_param($stmtUpdate, "ssi", $username, $email, $user_id);
    mysqli_stmt_execute($stmtUpdate);
    
    $message = "Profile updated successfully!";
    // Refresh user data
    $query = "SELECT username, email, profile_pic FROM users WHERE id=?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Profile - BookMahal</title>
  <style>
    /* Basic Reset */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #eef2f5;
      color: #2c3e50;
      padding-top: 80px; /* Space for fixed header */
    }
    /* Fixed Header */
    .header {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      background: #2c3e50;
      color: #ecf0f1;
      padding: 15px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      z-index: 1000;
    }
    .header .logo {
      font-size: 26px;
      font-weight: bold;
    }
    .nav-links {
      list-style: none;
      display: flex;
      gap: 20px;
    }
    .nav-links li a {
      text-decoration: none;
      color: #ecf0f1;
      padding: 8px 12px;
      border-radius: 4px;
      transition: background 0.3s;
    }
    .nav-links li a:hover,
    .nav-links li a.active {
      background: #3498db;
    }
    /* Main Container */
    .edit-container {
      max-width: 600px;
      margin: 20px auto;
      background: #fff;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .edit-container h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #2c3e50;
    }
    .edit-container form label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }
    .edit-container form input[type="text"],
    .edit-container form input[type="email"],
    .edit-container form input[type="password"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    .edit-container form input[type="file"] {
      margin-bottom: 15px;
    }
    .edit-container form button {
      width: 100%;
      padding: 10px;
      background: #3498db;
      border: none;
      border-radius: 4px;
      color: #fff;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s;
    }
    .edit-container form button:hover {
      background: #2980b9;
    }
    .message {
      text-align: center;
      margin-bottom: 20px;
      background: #f1c40f;
      padding: 10px;
      border-radius: 4px;
      color: #2c3e50;
    }
    .profile-pic {
      display: block;
      width: 150px;
      height: 150px;
      border-radius: 50%;
      object-fit: cover;
      margin: 0 auto 20px auto;
      border: 4px solid #3498db;
    }
  </style>
</head>
<body>
  <header class="header">
    <h1 class="logo">BookMahal</h1>
    <nav>
      <ul class="nav-links">
        <li><a href="home.php">Home</a></li>
        <li><a href="profile.php" class="active">Profile</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </nav>
  </header>

  <div class="edit-container">
    <?php if (!empty($message)): ?>
      <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <h2>Edit Profile</h2>
    <form action="edit_profile.php" method="POST" enctype="multipart/form-data">
      <label>Username:</label>
      <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

      <label>Email:</label>
      <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

      <label>Profile Picture:</label>
      <img src="<?php echo htmlspecialchars($user['profile_pic'] ?? 'default-profile.png'); ?>" alt="Profile Picture" class="profile-pic">
      <input type="file" name="profile_pic" accept="image/*">
      
      <button type="submit">Save Changes</button>
    </form>
  </div>
</body>
</html>
