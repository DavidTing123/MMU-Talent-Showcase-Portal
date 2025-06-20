<?php
require_once "auth.php";
require_once "db_connect.php";

$user_id = $_SESSION["user_id"];
$message = "";

// ✅ 檢查是否有傳作品 ID
if (!isset($_GET["id"])) {
    die("❌ No portfolio ID provided.");
}

$portfolio_id = $_GET["id"];

// ✅ 查詢該作品
$stmt = $conn->prepare("SELECT * FROM portfolio WHERE portfolio_id = ? AND user_id = ?");
$stmt->execute([$portfolio_id, $user_id]);
$portfolio = $stmt->fetch();

if (!$portfolio) {
    die("❌ You are not authorized to edit this portfolio.");
}

// ✅ 處理表單提交
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $description = htmlspecialchars(trim($_POST["description"]));
    $price = floatval($_POST["price"]);

    $update = $conn->prepare("UPDATE portfolio SET description = ?, price = ? WHERE portfolio_id = ? AND user_id = ?");
    $update->execute([$description, $price, $portfolio_id, $user_id]);

    $message = "✅ Portfolio updated successfully.";
    // 重新查詢最新資料
    $stmt->execute([$portfolio_id, $user_id]);
    $portfolio = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Portfolio</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Edit Portfolio</h2>

    <?php if ($message): ?>
        <p style="color:green;"><?= $message ?></p>
    <?php endif; ?>

    <p><strong>Title:</strong> <?= htmlspecialchars($portfolio["title"]) ?></p>
    <p><strong>Category:</strong> <?= htmlspecialchars($portfolio["category"]) ?></p>

    <form method="POST">
        <label>Description:</label><br>
        <textarea name="description" rows="5" cols="50"><?= htmlspecialchars($portfolio["description"]) ?></textarea><br><br>

        <label>Price (RM):</label><br>
        <input type="number" name="price" value="<?= htmlspecialchars($portfolio["price"]) ?>" step="0.01" required><br><br>

        <button type="submit">Save Changes</button>
    </form>

    <br>
    <a href="profile.php">← Back to Profile</a>
</body>
</html>
