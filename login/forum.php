<?php 
session_start();
require_once "db_connect.php";

$error = "";

// Handle topic submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit_topic"])) {
    $name = trim($_POST['name']);
    $mainTitle = trim($_POST['mainTitle']);
    $desc = trim($_POST['desc']);
    $topic = trim($_POST['topic']);
    $validTopics = ["Writing", "Music", "Technology", "Art"];

    if ($name && $mainTitle && $desc && in_array($topic, $validTopics)) {
        $mediaPath = null;
        if (isset($_FILES["media"]) && $_FILES["media"]["error"] === 0) {
            $media = $_FILES["media"];
            $ext = strtolower(pathinfo($media["name"], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'mp4'];
            if (in_array($ext, $allowed)) {
                $uploadDir = "uploads/";
                if (!is_dir($uploadDir)) mkdir($uploadDir);
                $mediaPath = $uploadDir . "forum_" . time() . "_" . basename($media["name"]);
                move_uploaded_file($media["tmp_name"], $mediaPath);
            } else {
                $error = "Invalid media type. Allowed: jpg, png, gif, mp4.";
            }
        }

        if (!$error) {
            // Set user_id to NULL if not logged in
            $user_id = $_SESSION["user_id"] ?? null;
            $stmt = $conn->prepare("INSERT INTO forum_topics (user_id, name, main_title, description, topic_category, media_path) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $name, $mainTitle, $desc, $topic, $mediaPath]);
            header("Location: forum.php");
            exit();
        }
    } else {
        $error = "Please fill all fields and choose a valid topic.";
    }
}

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
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
        }

        header {
            background-color: #d8cdbd;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #aaa;
            position: relative;
        }

        .logo {
            height: 40px;
        }

        h1 {
            margin: 0;
            font-size: 24px;
            flex-grow: 1;
            text-align: center;
        }

        .menu-icon {
            font-size: 24px;
            cursor: pointer;
            padding: 10px;
        }

        .menu-popup {
            display: none;
            position: absolute;
            top: 60px;
            right: 10px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            z-index: 999;
        }

        .menu-popup.active {
            display: block;
        }

        .menu-popup a {
            display: block;
            padding: 12px 20px;
            text-decoration: none;
            color: #333;
            border-bottom: 1px solid #eee;
        }

        .menu-popup a:hover {
            background-color: #f2f2f2;
        }

        main {
            padding: 20px;
        }

        h2 {
            margin-top: 0;
        }

        .card {
            background-color: #f2ece3;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 2px 2px 6px #aaa;
            cursor: pointer;
        }

        .user-info {
            font-size: 14px;
            color: #333;
            margin-bottom: 5px;
        }

        .title {
            font-size: 16px;
            font-weight: bold;
        }

        form input, form textarea, form select {
            width: 100%;
            margin-bottom: 10px;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #aaa;
        }

        form button {
            padding: 8px 16px;
            background-color: #00aaff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        form button:hover {
            background-color: #0077cc;
        }

        #topicForm {
            display: none;
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>

<header>
    <a href="index.php">
        <img src="image/mmu-logo.png" alt="MMU Logo" class="logo">
    </a>
    <h1>Forum</h1>
    <div class="menu-icon" onclick="document.querySelector('.menu-popup').classList.toggle('active')">☰</div>
    <div class="menu-popup">
        <a href="index.php">Home</a>
        <a href="catalogue.php">Catalogue</a>
        <a href="forum.php">Forum</a>
        <a href="FAQs.php">FAQs</a>
        <a href="registration.php">Sign Up</a>
    </div>
</header>

<main>
    <h2>Welcome!</h2>

    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <button onclick="document.getElementById('topicForm').style.display='block'">+ Add a Topic</button>

    <form id="topicForm" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="submit_topic" value="1">
        <input type="text" name="name" placeholder="Enter your name" required>
        <input type="text" name="mainTitle" placeholder="Enter main title" required>
        <textarea name="desc" placeholder="Enter description" required></textarea>
        <select name="topic" required>
            <option value="">Select Topic</option>
            <option value="Writing">Writing</option>
            <option value="Music">Music</option>
            <option value="Technology">Technology</option>
            <option value="Art">Art</option>
        </select>
        <input type="file" name="media" accept="image/*,video/mp4"><br>
        <button type="submit">OK</button>
    </form>

    <div id="topics">
        <?php foreach ($topics as $topic): ?>
            <div class="card" onclick="location.href='view.php?id=<?= $topic['id'] ?>'">
                <div class="user-info">
                    <span class="username"><?= htmlspecialchars($topic['name']) ?></span> 
                    <span class="topic"><?= htmlspecialchars($topic['topic_category']) ?></span>
                </div>
                <div class="title"><?= htmlspecialchars($topic['main_title']) ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</main>

</body>
</html>
