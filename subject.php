<?php
session_start();
include 'db_connect.php'; // Database Connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subjects - BookMahal</title>
    <link rel="stylesheet" href="suject.css">

</head>
<body>

<header class="header">
    <h1 class="logo">BookMahal</h1>
    <nav>
        <ul class="nav-links">
            <li><a href="home.php">Home</a></li>
            <li><a href="mybooks.php">My Books</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="help.php">Help & Support</a></li>
        </ul>
    </nav>
</header>

<main>
    <h2>Filter Books</h2>
    <form id="filterForm">
        <label for="language">Select Language:</label>
        <select name="language" id="language">
            <option value="">All</option>
            <?php
            $languages =[
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
        <select name="subject" id="subject">
            <option value="">All</option>
            <?php
            $subjects = ["Accounting", "Art History", "Arts and Humanities", "Biology", "Business and Management",
                "Chemistry", "Classical Studies", "Computing and IT", "Counselling", "Creative Arts", "Creative Writing",
                "Criminology", "Design", "Drawing", "Early Years", "Economics", "Education", "Electronic Engineering",
                "Engineering", "English", "Environment", "Film and Media", "Finance", "Fine Art", "Geography",
                "Graphic Design", "Health and Social Care", "Health and Wellbeing", "Health Sciences", "History",
                "Illustration", "Interior Design", "International Studies", "Languages", "Law", "Marketing", "Mathematics",
                "Mental Health", "Music", "Nursing and Healthcare", "Painting", "Philosophy", "Photography", "Physics",
                "Politics","biography", "Psychology", "Religious Studies", "movie script", "Social Sciences", "Social Work", "Sociology",
                "Software Engineering", "Sport & Fitness", "Statistics", "Textiles","Textiles","love story","Religious", "Visual Communications",
    "Accounting", "Agriculture", "Anthropology", "Archaeology", "Architecture", "Art History", 
    "Artificial Intelligence", "Astronomy", "Aviation", "Banking", "Biochemistry", "Bioinformatics", 
    "Biology", "Biomedical Science", "Biotechnology", "Botany", "Business and Management", 
    "Chemical Engineering", "Chemistry", "Civil Engineering", "Classical Studies", "Commerce", 
    "Communication", "Comparative Literature", "Computing and IT", "Construction", "Counselling", 
    "Creative Arts", "Creative Writing", "Criminology", "Culinary Arts", "Data Science", 
    "Defence Studies", "Dentistry", "Design", "Digital Marketing", "Drawing", "Early Childhood Education", 
    "Economics", "Education", "Electrical Engineering", "Electronics", "Energy Studies", 
    "Engineering", "English", "Entrepreneurship", "Environmental Science", "Fashion Design", 
    "Film and Media", "Finance", "Fine Art", "Food Technology", "Forestry", "Game Development", 
    "Genetics", "Geography", "Geology", "Graphic Design", "Health and Social Care", 
    "Health and Wellbeing", "Health Sciences", "History", "Hospitality Management", "Human Resources", 
    "Illustration", "Industrial Engineering", "Information Technology", "Interior Design", 
    "International Business", "International Relations", "International Studies", "Journalism", 
    "Languages", "Law", "Library Science", "Linguistics", "Machine Learning", "Management Studies", 
    "Manufacturing", "Marketing", "Material Science", "Mathematics", "Mechanical Engineering", 
    "Media Studies", "Medical Science", "Mental Health", "Metallurgy", "Microbiology", 
    "Military Science", "Mining Engineering", "Music", "Nanotechnology", "Nursing and Healthcare", 
    "Nutrition", "Oceanography", "Painting", "Petroleum Engineering", "Pharmaceutical Sciences", 
    "Philosophy", "Photography", "Physics", "Physiotherapy", "Political Science", "Politics", 
    "Psychiatry", "Psychology", "Public Administration", "Public Health", "Religious Studies", 
    "Renewable Energy", "Robotics", "Rural Development", "Science", "Social Sciences", "Social Work", 
    "Sociology", "Software Engineering", "Space Science", "Special Education", "Sport & Fitness", 
    "Statistics", "Supply Chain Management", "Sustainable Development", "Taxation", "Teacher Education", 
    "Telecommunication", "Textiles", "Tourism Management", "Toxicology", "Transportation", 
    "Urban Planning", "Veterinary Science", "Visual Communications", "Wildlife Conservation", 
    "Zoology", "Biography", "Love Story", "Religious"
];

            foreach ($subjects as $sub) {
                echo "<option value='$sub'>$sub</option>";
            }
            ?>
        </select>

        <button type="submit">Apply Filters</button>
    </form>

    <h2>Available Books</h2>
    <div class="books-grid" id="booksContainer">
        
        <!-- Filtered books will be displayed here -->
    </div>
</main>

<script>
document.getElementById('filterForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent form from reloading

    let language = document.getElementById('language').value;
    let subject = document.getElementById('subject').value;

    fetch(`filter.php?language=${language}&subject=${subject}`)
        .then(response => response.text())
        .then(data => {
            document.getElementById('booksContainer').innerHTML = data;
        });
});
</script>

</body>
</html>
