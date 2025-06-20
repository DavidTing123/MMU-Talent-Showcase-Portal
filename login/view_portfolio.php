<?php
require_once "auth.php";
require_once "db_connect.php";

$user_id = $_SESSION["user_id"];
$portfolio_id = $_GET["id"] ?? null;
$back = $_GET["back"] ?? "profile.php"; // 預設回 profile

if (!$portfolio_id) {
    die("❌ No portfolio ID provided.");
}

$stmt = $conn->prepare("SELECT * FROM portfolio WHERE portfolio_id = ?");
$stmt->execute([$portfolio_id]);
$work = $stmt->fetch();

if (!$work) {
    die("❌ Portfolio not found.");
}

$filePath = $work["file_path"];
$fileType = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($work["title"]) ?></title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .container { max-width: 800px; margin: 20px auto; font-family: sans-serif; }
        .video, .image { width: 100%; border-radius: 10px; }
        .details { margin-top: 20px; padding: 15px; background: #f5f5f5; border-radius: 8px; }
        .details p { margin: 8px 0; }
        .back-btn {
            display:inline-block; margin-top:20px;
            padding: 8px 16px; background: #ccc;
            text-decoration: none; border-radius: 6px; color: black;
        }
    </style>
</head>
<body>
<div class="container">

    <h2><?= htmlspecialchars($work["title"]) ?></h2>

    <?php if (in_array($fileType, ['jpg', 'jpeg', 'png', 'gif'])): ?>
        <img src="<?= $filePath ?>" class="image">
    <?php elseif ($fileType === 'mp4'): ?>
        <video class="video" controls autoplay>
            <source src="<?= $filePath ?>" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    <?php else: ?>
        <p>This file format is not supported for preview. <a href="<?= $filePath ?>" target="_blank">Download</a></p>
    <?php endif; ?>

    <div class="details">
        <p><strong>Category:</strong> <?= htmlspecialchars($work["category"]) ?></p>
        <p><strong>Description:</strong><br><?= nl2br(htmlspecialchars($work["description"])) ?></p>
        <p><strong>Price:</strong> RM <?= number_format($work["price"], 2) ?></p>
    </div>

    <a href="<?= htmlspecialchars($back) ?>" class="back-btn">← Back</a>

</div>
</body>
</html>
