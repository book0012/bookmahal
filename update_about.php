<?php
session_start();
include 'db_connect.php'; // Database Connection

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

// Fetch existing data
$query = "SELECT * FROM about_us WHERE id=1 LIMIT 1";
$result = mysqli_query($conn, $query);
$about = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    // Handle Image Upload
    if (!empty($_FILES['photo']['name'])) {
        $photo_name = basename($_FILES['photo']['name']);
        $target_dir = "uploads/";
        $target_file = $target_dir . $photo_name;
        move_uploaded_file($_FILES['photo']['tmp_name'], $target_file);
    } else {
        $target_file = $about['photo'];
    }
    
    // Update Query
    $update_query = "UPDATE about_us SET name='$name', email='$email', description='$description', photo='$target_file' WHERE id=1";
    mysqli_query($conn, $update_query);
    header('Location: about.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit About Us</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .container { width: 50%; margin: auto; background: #f4f4f4; padding: 20px; border-radius: 10px; }
        input, textarea { width: 100%; padding: 10px; margin: 5px 0; border-radius: 5px; border: 1px solid #ccc; }
        button { background: #28a745; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer; }
        img { max-width: 100px; display: block; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit About Us</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <label>Name:</label>
            <input type="text" name="name" value="<?php echo $about['name']; ?>" required>
            
            <label>Email:</label>
            <input type="email" name="email" value="<?php echo $about['email']; ?>" required>
            
            <label>Description:</label>
            <textarea name="description" required><?php echo $about['description']; ?></textarea>
            
            <label>Current Photo:</label>
            <img src="<?php echo $about['photo']; ?>" alt="Profile Picture">
            
            <label>Change Photo:</label>
            <input type="file" name="photo" accept="image/*">
            
            <button type="submit">Save Changes</button>
        </form>
    </div>
</body>
</html>
