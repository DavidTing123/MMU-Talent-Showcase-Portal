<?php
$host = 'localhost';
$dbname = 'talent_portal';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
// Simulated data (replace with database in future)
$announcements = [
    ["title" => "Exam Timetable", "description" => "Final exams will begin next week.", "date" => "5/5/2025"],
    ["title" => "New Workshop", "description" => "Join our AI workshop on 10 May.", "date" => "3/5/2025"]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Announcements - MMU Talent</title>
    <link rel="stylesheet" href="css/annoucement.css">
</head>
<body>

<header class="navbar">
    <div class="logo">MMU Talent</div>
    <nav>
        <ul>
            <li><a href="admin.php">Users</a></li>
            <li><a href="announcements.php" class="active">Announcements</a></li>
            <li><a href="faq.php">FAQs</a></li>
            <li><a href="profile.php">&#128100;</a></li>
            <li><a href="#">&#9776;</a></li>
        </ul>
    </nav>
</header>

<section class="banner">
    <h1>Welcome Admin!</h1>
</section>

<div class="announcements-container">
    <div class="top-bar">
        <h2>Recent Announcements</h2>
        <button class="post-btn">Post new announcement</button>
    </div>

    <?php foreach ($announcements as $item): ?>
        <div class="announcement">
            <div class="announcement-title"><?= htmlspecialchars($item['title']) ?></div>
            <div class="announcement-description"><?= htmlspecialchars($item['description']) ?></div>
            <div class="announcement-date"><?= htmlspecialchars($item['date']) ?></div>
            <div class="announcement-actions">
                <button class="edit-btn">Edit</button>
                <button class="delete-btn">Delete</button>
            </div>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>
