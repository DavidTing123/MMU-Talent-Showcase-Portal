<?php
// forum.php — Forum page with SQL-based topic storage

require_once "db_connect.php"; // Connect to the database
// require_once "auth.php";       // Get the logged-in user ID from session

$error = "";
$success = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit_topic'])) {
    $name = trim($_POST['name']);
    $mainTitle = trim($_POST['mainTitle']);
    $desc = trim($_POST['desc']);
    $topic = trim($_POST['topic']);

    $validTopics = ["Writing", "Music", "Technology", "Art"];
    $mediaPath = "";

    // Validate fields
    if ($name && $mainTitle && $desc && in_array($topic, $validTopics)) {

        // Handle media upload (image/video)
        if (isset($_FILES["media"]) && $_FILES["media"]["error"] === 0) {
            $ext = strtolower(pathinfo($_FILES["media"]["name"], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'mp4', 'webm'];

            if (in_array($ext, $allowed)) {
                $uploadDir = "uploads/";
                $newFilename = uniqid("media_") . "_" . time() . "." . $ext;
                $mediaPath = $uploadDir . $newFilename;

                move_uploaded_file($_FILES["media"]["tmp_name"], $mediaPath);
            } else {
                $error = "Invalid media file type.";
            }
        }

        // Insert topic into database
        if (!$error) {
            $stmt = $conn->prepare("
                INSERT INTO forum_topics (user_id, name, main_title, description, topic_category, media_path)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$user_id, $name, $mainTitle, $desc, $topic, $mediaPath]);

            $success = "Topic added successfully!";
            header("Location: forum.php"); // Reload to prevent form resubmission
            exit();
        }
    } else {
        $error = "Please fill all fields correctly. Only Writing, Music, Technology, or Art allowed.";
    }
}

// Fetch all forum topics from database
$stmt = $conn->query("SELECT * FROM forum_topics ORDER BY created_at DESC");
$topics = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forum</title>
    <link rel="stylesheet" href="css/forum.css">
    <style>
        /* Only add CSS to hide form initially */
        #topicForm {
            display: none;
        }
    </style>
</head>
<body>
<header>
    <a href="index.php">
        <img src="image/mmu-logo.png" alt="MMU Logo" class="logo">
    </a>
    <h1>Forum</h1>
    <div class="header-right">
        <input type="text" id="searchInput" placeholder="Search" onkeyup="searchTopics()">
        <a href="profile.php">
            <img src="image/profile-icon.png" alt="Profile" class="profile-icon">
        </a>
    </div>
</header>

<main>
    <h2>Welcome!</h2>

    <!-- Display error/success messages -->
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <?php if (!empty($success)) echo "<p style='color:green;'>$success</p>"; ?>

    <!-- Add a Topic Button -->
    <button onclick="document.getElementById('topicForm').style.display='block'">+ Add a Topic</button>

    <!-- Topic Submission Form -->
    <form id="topicForm" method="POST" enctype="multipart/form-data" style="max-width:500px; margin-top:20px; padding:20px; background:#fff; box-shadow:0 0 10px rgba(0,0,0,0.1);">
        <input type="hidden" name="submit_topic" value="1">
        <input type="text" name="name" placeholder="Enter your name" required><br>
        <input type="text" name="mainTitle" placeholder="Enter main title" required><br>
        <textarea name="desc" placeholder="Enter description" required></textarea><br>
        <select name="topic" required>
            <option value="">Select Topic</option>
            <option value="Writing">Writing</option>
            <option value="Music">Music</option>
            <option value="Technology">Technology</option>
            <option value="Art">Art</option>
        </select><br>
        <input type="file" name="media" accept="image/*,video/*"><br><br>
        <button type="submit">OK</button>
    </form>

    <!-- List of Topics -->
    <div id="topics">
        <?php foreach ($topics as $item): ?>
            <div class="card" onclick="location.href='view.php?id=<?= $item['id'] ?>'">
                <div class="user-info">
                    <span class="username"><?= htmlspecialchars($item['name']) ?></span>
                    <span class="topic"><?= htmlspecialchars($item['topic_category']) ?></span>
                </div>
                <div class="title"><?= htmlspecialchars($item['main_title']) ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<!-- JavaScript for live search -->
<script>
function searchTopics() {
    const input = document.getElementById("searchInput").value.toLowerCase();
    const cards = document.getElementsByClassName("card");
    for (let card of cards) {
        const text = card.innerText.toLowerCase();
        card.style.display = text.includes(input) ? "block" : "none";
    }
}
</script>

</body>
</html>
