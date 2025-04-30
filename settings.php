<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'db_connect.php';

$user_id = $_SESSION['user_id'];

// Fetch user details
$query = "SELECT * FROM users WHERE id=?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Change Password
    if (isset($_POST['change_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Verify current password (assuming password stored as hash)
        if (password_verify($current_password, $user['password'])) {
            if ($new_password === $confirm_password) {
                $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
                $updateQuery = "UPDATE users SET password=? WHERE id=?";
                $stmt_update = mysqli_prepare($conn, $updateQuery);
                mysqli_stmt_bind_param($stmt_update, "si", $new_hash, $user_id);
                mysqli_stmt_execute($stmt_update);
                $message = "Password changed successfully!";
            } else {
                $message = "New passwords do not match!";
            }
        } else {
            $message = "Current password is incorrect!";
        }
    }

    // Change Email
    if (isset($_POST['change_email'])) {
        $new_email = $_POST['new_email'];
        // Additional email validation can be added here
        $updateQuery = "UPDATE users SET email=? WHERE id=?";
        $stmt_update = mysqli_prepare($conn, $updateQuery);
        mysqli_stmt_bind_param($stmt_update, "si", $new_email, $user_id);
        mysqli_stmt_execute($stmt_update);
        $message = "Email changed successfully!";
    }

    // Change Phone Number
    if (isset($_POST['change_phone'])) {
        $new_phone = $_POST['new_phone'];
        // Additional phone validation can be added here
        $updateQuery = "UPDATE users SET phone=? WHERE id=?";
        $stmt_update = mysqli_prepare($conn, $updateQuery);
        mysqli_stmt_bind_param($stmt_update, "si", $new_phone, $user_id);
        mysqli_stmt_execute($stmt_update);
        $message = "Phone number changed successfully!";
    }

    // Change Mode (Day/Night)
    if (isset($_POST['change_mode'])) {
        $mode = $_POST['mode']; // Expected values: 'day' or 'night'
        $updateQuery = "UPDATE users SET mode=? WHERE id=?";
        $stmt_update = mysqli_prepare($conn, $updateQuery);
        mysqli_stmt_bind_param($stmt_update, "si", $mode, $user_id);
        mysqli_stmt_execute($stmt_update);
        $message = "Display mode updated!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - BookMahal</title>
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
            font-weight: 500;
            padding: 8px 12px;
            border-radius: 4px;
            transition: background 0.3s, color 0.3s;
        }
        .nav-links li a:hover,
        .nav-links li a.active {
            background-color: #3498db;
            color: #ecf0f1;
        }
        /* Main Container */
        .settings-container {
            max-width: 800px;
            margin: 100px auto 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .settings-container h2 {
            margin-bottom: 15px;
            color: #2c3e50;
            font-size: 24px;
        }
        .settings-container form {
            margin-bottom: 30px;
        }
        .settings-container label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .settings-container input[type="text"],
        .settings-container input[type="email"],
        .settings-container input[type="password"],
        .settings-container select,
        .settings-container textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .settings-container textarea {
            resize: vertical;
            min-height: 80px;
        }
        .settings-container button {
            background: #3498db;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .settings-container button:hover {
            background: #2980b9;
        }
        .message {
            background: #f1c40f;
            color: #2c3e50;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
        }
        /* For spacing below header */
        main {
            margin-top: 80px;
        }
    </style>
</head>
<body>
    <header class="header">
        <h1 class="logo">BookMahal</h1>
        <nav>
            <ul class="nav-links">
                <li><a href="home.php">Home</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="logout.php" class="btn">Logout</a></li>
            </ul>
        </nav>
    </header>
    
    <div class="settings-container">
        <?php if (!empty($message)): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <!-- Change Password -->
        <h2>Change Password</h2>
        <form action="settings.php" method="POST">
            <label>Current Password:</label>
            <input type="password" name="current_password" required>
            
            <label>New Password:</label>
            <input type="password" name="new_password" required>
            
            <label>Confirm New Password:</label>
            <input type="password" name="confirm_password" required>
            
            <button type="submit" name="change_password">Change Password</button>
        </form>

        <!-- Change Email -->
        <h2>Change Email</h2>
        <form action="settings.php" method="POST">
            <label>New Email:</label>
            <input type="email" name="new_email" required>
            <button type="submit" name="change_email">Change Email</button>
        </form>

        <!-- Change Phone Number -->
        <h2>Change Phone Number</h2>
        <form action="settings.php" method="POST">
            <label>New Phone Number:</label>
            <input type="text" name="new_phone" required>
            <button type="submit" name="change_phone">Change Phone</button>
        </form>

        <!-- Change Mode (Day/Night) -->
        <h2>Display Mode</h2>
        <form action="settings.php" method="POST">
            <label>Select Mode:</label>
            <select name="mode" required>
                <option value="day" <?php if($user['mode'] == 'day') echo 'selected'; ?>>Day Mode</option>
                <option value="night" <?php if($user['mode'] == 'night') echo 'selected'; ?>>Night Mode</option>
            </select>
            <button type="submit" name="change_mode">Update Mode</button>
        </form>
    </div>
</body>
</html>
