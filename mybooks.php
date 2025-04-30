<?php
session_start();
include 'db_connect.php'; // Database Connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id']; // Current Logged-in User

// Create Uploads Folder if Not Exists
if (!is_dir('uploads')) {
    mkdir('uploads', 0777, true);
}

// Handle Book Upload
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $language = mysqli_real_escape_string($conn, $_POST['language']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);

    $cover_folder = "";
    $pdf_folder = "";

    // File Upload Handling (Book Cover)
    if (isset($_FILES['cover']) && $_FILES['cover']['error'] == 0) {
        $cover_image = basename($_FILES['cover']['name']);
        $cover_tmp = $_FILES['cover']['tmp_name'];
        $cover_folder = "uploads/" . $cover_image;
        move_uploaded_file($cover_tmp, $cover_folder);
    }

    // File Upload Handling (PDF File)
    if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] == 0) {
        $pdf_file = basename($_FILES['pdf']['name']);
        $pdf_tmp = $_FILES['pdf']['tmp_name'];
        $pdf_folder = "uploads/" . $pdf_file;
        move_uploaded_file($pdf_tmp, $pdf_folder);
    }

    // Insert into Database with User ID
    $query = "INSERT INTO books (user_id, title, author, language, subject, cover, pdf) VALUES ('$user_id', '$title', '$author', '$language', '$subject', '$cover_folder', '$pdf_folder')";
    if (mysqli_query($conn, $query)) {
        $_SESSION['message'] = "Book Added Successfully!";
    } else {
        $_SESSION['message'] = "Error: " . mysqli_error($conn);
    }
}

// Fetch Books Uploaded by Logged-in User
$book_query = "SELECT * FROM books WHERE user_id = '$user_id'";
$book_result = mysqli_query($conn, $book_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Books - BookMahal</title>
    <style>
        /* CSS Styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f4f4;
        }
        .header {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: white;
            padding: 15px 20px;
            text-align: center;
            font-size: 24px;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .container {
            width: 80%;
            margin: 100px auto 20px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .message {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        .books-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .book-card {
            background: white;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 200px;
            text-align: center;
        }
        .book-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 10px;
        }
        .book-card h3 {
            font-size: 18px;
            margin: 10px 0;
        }
        .upload-form {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .upload-form input, .upload-form select, .upload-form button {
            display: block;
            width: 100%;
            margin: 10px 0;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .upload-form button {
            background: #28a745;
            color: white;
            font-size: 16px;
            cursor: pointer;
            border: none;
        }
        .upload-form button:hover {
            background: #218838;
        }
    </style>
</head>
<body>

<header class="header">
    <a href="home.php">🏠</a>BookMahal - My Books
</header>

<div class="container">
    <h2>Upload New Book</h2>

    <?php if (isset($_SESSION['message'])): ?>
        <p class="message"> <?php echo $_SESSION['message']; unset($_SESSION['message']); ?> </p>
    <?php endif; ?>

    <form class="upload-form" method="POST" action="mybooks.php" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Book Title" required>
        <input type="text" name="author" placeholder="Author Name" required>

        <label for="language">Select Language:</label>
        <select name="language" required>
            <option value="">Choose Language</option>
            <?php
            $languages = [
    "Afrikaans", "Albanian", "Amharic", "Arabic", "Armenian", "Aymara", "Azerbaijani",
    "Balinese", "Basque", "Belarusian", "Bosnian", "Breton", "Bulgarian", "Burmese",
    "Cantonese", "Catalan", "Cebuano", "Chamorro", "Chechen", "Chinese (Mandarin)", 
    "Cornish", "Corsican", "Crimean Tatar", "Croatian", "Czech", "Danish", "Dari",
    "Dutch", "Dzongkha", "English", "Esperanto", "Estonian", "Ewe", "Faroese",
    "Farsi (Persian)", "Fijian", "Filipino", "Finnish", "Flemish", "French", "Frisian",
    "Galician", "Georgian", "German", "Greek", "Greenlandic", "Guarani", "Haitian Creole",
    "Hakka Chinese", "Hausa", "Hawaiian", "Hebrew", "Hmong", "Hungarian", "Icelandic",
    "Igbo", "Ilocano", "Indonesian", "Inuktitut", "Irish", "Italian", "Japanese",
    "Javanese", "Kazakh", "Kinyarwanda", "Komi", "Korean", "Kurdish", "Kyrgyz",
    "Ladino", "Lao", "Latin", "Latvian", "Limburgish", "Lingala", "Lithuanian",
    "Lombard", "Luganda", "Luxembourgish", "Macedonian", "Malay", "Malagasy",
    "Maltese", "Mayan", "Mongolian", "Montenegrin", "Nahuatl", "Navajo", "Norwegian",
    "Oghuz", "Ojibwe", "Pashto", "Polish", "Portuguese", "Quechua", "Romanian",
    "Romani", "Russian", "Samoan", "Sanskrit", "Sardinian", "Scottish Gaelic",
    "Serbian", "Shona", "Sinhala", "Sino-Tibetan", "Slovak", "Slovene", "Somali",
    "Spanish", "Sundanese", "Swahili", "Swedish", "Swiss German", "Tagalog", "Tajik",
    "Tamashek", "Tatar", "Tetum", "Thai", "Tibetan", "Tigrinya", "Tok Pisin", "Tongan",
    "Tswana", "Turkish", "Turkmen", "Twi", "Uighur", "Ukrainian", "Uzbek",
    "Vietnamese", "Welsh", "Wolof", "Xhosa", "Yiddish", "Yoruba", "Zulu","Ahirani", "Angika", "Assamese", "Awadhi", "Bagheli", "Bagri", "Banjari",
    "Bengali", "Bhadrawahi", "Bhojpuri", "Bodo/Boro", "Bhili/Bhilodi",
    "Bishnupriya Manipuri", "Brajbhasha", "Bundi", "Chambeali", "Chhattisgarhi",
    "Dakhini", "Darai", "Dhundhari", "Dogri", "Garhwali", "Garo", "Gondi",
    "Gujarati", "Hara/Harauti", "Haryanvi", "Hindi", "Ho", "Kachchhi", "Kannada",
    "Kashmiri", "Khasi", "Khortha/Khotta", "Kinnauri", "Kokborok", "Konkani",
    "Kurukh/Oraon", "Ladakhi", "Lambani/Lambadi", "Lepcha", "Maithili",
    "Magahi", "Malvi", "Manipuri (Meitei)", "Marathi", "Marwari", "Mewari",
    "Mizo (Lushai)", "Mundari", "Nagpuri", "Nepali", "Nimadi", "Odia", "Pahari",
    "Pali", "Punjabi", "Rajasthani", "Sadan/Sadri", "Sambalpuri", "Santali",
    "Sindhi", "Surgujia", "Surjapuri", "Tamil", "Telugu", "Tulu", "Urdu", "Wagdi"
];
            foreach ($languages as $lang) {
                echo "<option value='$lang'>$lang</option>";
            }
            ?>
        </select>

        <label for="subject">Select Subject:</label>
        <select name="subject" required>
            <option value="">Choose Subject</option>
            <?php
            $subjects = ["Accounting", "Art History", "Arts and Humanities", "Biology", "Business and Management",
                "Chemistry", "Classical Studies", "Computing and IT", "Counselling", "Creative Arts", "Creative Writing",
                "Criminology", "Design", "Drawing", "Early Years", "Economics", "Education", "Electronic Engineering",
                "Engineering", "English", "Environment", "Film and Media", "Finance", "Fine Art", "Geography",
                "Graphic Design", "Health and Social Care", "Health and Wellbeing", "Health Sciences", "History",
                "Illustration", "Interior Design", "International Studies", "Languages", "Law", "Marketing", "Mathematics","love story",
                "Mental Health", "Music", "Nursing and Healthcare", "Painting", "Philosophy", "Photography", "Physics",
                "Politics", "Biography","Psychology", "Religious Studies", "Science", "Social Sciences", "Social Work", "Sociology",
                "Software Engineering", "Sport & Fitness", "Statistics", "Textiles","Textiles","Religious", "Visual Communications"];["Accounting", "Art History", "Arts and Humanities", "Biology", "Business and Management",
                "Chemistry", "Classical Studies", "Computing and IT", "Counselling", "Creative Arts", "Creative Writing",
                "Criminology", "Design", "Drawing", "Early Years", "Economics", "Education", "Electronic Engineering",
                "Engineering", "English", "Environment", "Film and Media", "Finance", "Fine Art", "Geography",
                "Graphic Design", "Health and Social Care", "Health and Wellbeing", "Health Sciences", "History",
                "Illustration", "Interior Design", "International Studies", "Languages", "Law", "Marketing", "Mathematics",
                "Mental Health", "Music", "Nursing and Healthcare", "Painting", "Philosophy", "Photography", "Physics",
                "Politics", "Psychology", "Religious Studies", "Science", "Social Sciences", "Social Work", "Sociology",
                "Software Engineering", "Sport & Fitness", "Statistics", "Textiles","Textiles","Religious", "Visual Communications"];
            foreach ($subjects as $sub) {
                echo "<option value='$sub'>$sub</option>";
            }
            ?>
        </select>

        <label>Upload Cover Image:</label>
        <input type="file" name="cover" accept="image/*" required>

        <label>Upload PDF:</label>
        <input type="file" name="pdf" accept="application/pdf" required>

         <label>Hard Copy Price (in ₹):</label>
    <input type="number" name="price" placeholder="Enter price" min="0" required>


        <button type="submit">Add Book</button>
    </form>

    <h2>My Uploaded Books</h2>
    <div class="books-grid">
        <?php while ($book = mysqli_fetch_assoc($book_result)) { ?>
            <div class="book-card">
                <img src="<?php echo $book['cover']; ?>" alt="Book Cover">
                <h3><?php echo $book['title']; ?></h3>
                <p><strong>Author:</strong> <?php echo $book['author']; ?></p>
                <p><strong>Language:</strong> <?php echo $book['language']; ?></p>
                <p><strong>Subject:</strong> <?php echo $book['subject']; ?></p>
                <a href="<?php echo $book['pdf']; ?>" download>📥 Download</a>
                <a href="edit_book.php?id=<?php echo $book['id']; ?>" class="btn">✏ Edit</a>

            </div>
        <?php } ?>
    </div>
</div>

</body>
</html>
