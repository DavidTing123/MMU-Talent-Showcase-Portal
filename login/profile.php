<?php
//session_start();
//if (!isset($_SESSION["user_id"])) {
//    header("Location: login.php");
//    exit;
//}
?> 

<?php

// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
// }
require_once "db_connect.php";
require_once "auth.php";

// // 確保使用者已登入
// if (!isset($_SESSION["user_id"])) {
//     header("Location: login.php");
//     exit;
// }

$user_id = $_SESSION["user_id"];
$email = "";
$category = "";
$bio = "";
$message = "";

// 查詢使用者基本資料
$stmt = $conn->prepare("
    SELECT u.email, p.talent_category, p.bio
    FROM users u
    JOIN user_profile p ON u.user_id = p.user_id
    WHERE u.user_id = ?
");
$stmt->execute([$user_id]);
$data = $stmt->fetch();

if ($data) {
    $email = $data["email"];
    $category = $data["talent_category"];
    $bio = $data["bio"];
}

// 處理簡介更新表單
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_bio = htmlspecialchars(trim($_POST["bio"]));

    $updateStmt = $conn->prepare("UPDATE user_profile SET bio = ? WHERE user_id = ?");
    $updateStmt->execute([$new_bio, $user_id]);

    $message = "Bio updated successfully!";
    $bio = $new_bio; // 更新畫面上的值
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
</head>
<body>
    <h2>Welcome to Your Profile</h2>
    <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
    <p><strong>Talent Category:</strong> <?= htmlspecialchars($category) ?></p>

    <h3>Edit Your Bio</h3>
    <form method="POST">
        <textarea name="bio" rows="5" cols="40"><?= htmlspecialchars($bio) ?></textarea><br><br>
        <button type="submit">Update Bio</button>
    </form>

    
    <br><br>

<?php
$stmt = $conn->prepare("SELECT * FROM portfolio WHERE user_id = ?");
$stmt->execute([$user_id]);
$portfolios = $stmt->fetchAll();

if ($portfolios) {
    echo "<h3>My Uploaded Works:</h3><div style='display:flex; flex-wrap:wrap; gap:20px;'>";

    foreach ($portfolios as $p) {
        $filePath = $p["file_path"];
        $fileType = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        echo "<div style='border:1px solid #ccc; padding:10px; width:250px;'>";

        if (in_array($fileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            // 顯示圖片
            echo "<img src='$filePath' alt='{$p['title']}' style='width:100%; height:auto;'><br>";
        } elseif ($fileType === 'mp4') {
            // 顯示影片播放器
            echo "<video controls width='100%'>
                    <source src='$filePath' type='video/mp4'>
                    Your browser does not support the video tag.
                  </video><br>";
        } else {
            // 其他檔案顯示下載連結
            echo "<p>[File]</p><a href='$filePath' target='_blank'>Download</a><br>";
        }

        echo "<strong>{$p['title']}</strong><br>";
        echo "<em>{$p['category']}</em>";
        echo "</div>";
    }

    echo "</div>";
}
?>

    <br><br>
    <a href="upload_portfolio.php">+ Upload New Work</a><br>

    <?php if ($message): ?>
        <p style="color:green;"><?= $message ?></p>
    <?php endif; ?>

    <br>
    <a href="logout.php">Logout</a>
</body>
</html>
