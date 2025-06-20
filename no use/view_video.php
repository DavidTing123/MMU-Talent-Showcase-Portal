<?php
require_once "auth.php";
require_once "db_connect.php";

if (!isset($_GET["id"])) {
    die("❌ No portfolio ID provided.");
}

$portfolio_id = $_GET["id"];
$user_id = $_SESSION["user_id"];

// Verify the video belongs to the user
$stmt = $conn->prepare("SELECT * FROM portfolio WHERE portfolio_id = ? AND user_id = ?");
$stmt->execute([$portfolio_id, $user_id]);
$data = $stmt->fetch();

if (!$data) {
    die("❌ Video not found or unauthorized.");
}

$from = isset($_GET["from"]) ? $_GET["from"] : "profile"; // 預設回 profile
$back_link = $from === "catalogue" ? "catalogue.php" : "profile.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($data["title"]) ?> | My Video</title>
    <link rel="stylesheet" href="css/view_video.css">
</head>
<body>

<div class="video-container">
    <video controls autoplay>
        <source src="<?= htmlspecialchars($data["file_path"]) ?>" type="video/mp4">
    </video>

    <div class="video-info">
        <h2><?= htmlspecialchars($data["title"]) ?></h2>
        <p><strong>Category:</strong> <?= htmlspecialchars($data["category"]) ?></p>
        <p><strong>Price:</strong> RM <?= number_format($data["price"], 2) ?></p>
        <p><strong>Description:</strong><br><?= nl2br(htmlspecialchars($data["description"])) ?></p>
    </div>

    <a href="<?= $back_link ?>">← Back to <?= ucfirst($from) ?></a>
</div>

</body>
</html>