<?php
require_once "auth.php";
require_once "db_connect.php";

if (!isset($_GET["id"])) {
    die("❌ Portfolio ID not provided.");
}

$portfolio_id = $_GET["id"];
$user_id = $_SESSION["user_id"];

// 查詢作品
$stmt = $conn->prepare("SELECT * FROM portfolio WHERE portfolio_id = ? AND user_id = ?");
$stmt->execute([$portfolio_id, $user_id]);
$portfolio = $stmt->fetch();

if (!$portfolio) {
    die("❌ Portfolio not found or access denied.");
}

$fileType = strtolower(pathinfo($portfolio["file_path"], PATHINFO_EXTENSION));
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($portfolio["title"]) ?> - Details</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2><?= htmlspecialchars($portfolio["title"]) ?></h2>
    <p><strong>Category:</strong> <?= htmlspecialchars($portfolio["category"]) ?></p>

    <?php if (in_array($fileType, ['jpg', 'jpeg', 'png', 'gif'])): ?>
        <img src="<?= $portfolio["file_path"] ?>" style="max-width:100%; border-radius:8px;" onclick="this.requestFullscreen()">
    <?php elseif ($fileType === 'mp4'): ?>
        <video controls style="width:100%; border-radius:8px;" onclick="this.requestFullscreen()">
            <source src="<?= $portfolio["file_path"] ?>" type="video/mp4">
        </video>
    <?php endif; ?>

    <div style="margin-top:20px;">
        <h4>Description</h4>
        <p><?= nl2br(htmlspecialchars($portfolio["description"])) ?: "No description available." ?></p>

        <h4>Price</h4>
        <p style="color:green; font-weight:bold;">RM <?= number_format($portfolio["price"], 2) ?></p>
    </div>

    <br>
    <a href="profile.php">← Back to Profile</a>
</body>
</html>
