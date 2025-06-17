<?php
require_once "db_connect.php";

// 抓全部作品
$stmt = $conn->prepare("
    SELECT p.title, p.file_path, p.category, u.email
    FROM portfolio p
    JOIN users u ON p.user_id = u.user_id
    ORDER BY p.upload_date DESC
");
$stmt->execute();
$portfolios = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Talent Showcase</title>
</head>
<body>
    <h2>All Talents</h2>
    <div style="display:flex; flex-wrap:wrap; gap:20px;">
        <?php foreach ($portfolios as $p): 
            $fileType = strtolower(pathinfo($p["file_path"], PATHINFO_EXTENSION));
        ?>
        <div style="border:1px solid #ccc; padding:10px; width:250px;">
            <strong><?= htmlspecialchars($p["title"]) ?></strong><br>
            <em><?= $p["category"] ?> by <?= $p["email"] ?></em><br><br>

            <?php if (in_array($fileType, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                <img src="<?= $p["file_path"] ?>" style="width:100%; height:auto;"><br>
            <?php elseif ($fileType === 'mp4'): ?>
                <video controls width="100%">
                    <source src="<?= $p["file_path"] ?>" type="video/mp4">
                </video><br>
            <?php else: ?>
                <a href="<?= $p["file_path"] ?>" target="_blank">Download File</a>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
