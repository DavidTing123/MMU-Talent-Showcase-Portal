<?php
require_once "auth.php";       // 檢查是否登入
require_once "db_connect.php"; // 資料庫連線

$user_id = $_SESSION["user_id"];
$category = "";
$bio = "";
$message = "";

// Step 1：讀取使用者原本資料
$stmt = $conn->prepare("SELECT talent_category, bio FROM user_profile WHERE user_id = ?");
$stmt->execute([$user_id]);
$data = $stmt->fetch();

if ($data) {
    $category = $data["talent_category"];
    $bio = $data["bio"];
}

// Step 2：如果表單被提交，就更新資料
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_category = $_POST["category"];
    $new_bio = htmlspecialchars(trim($_POST["bio"]));

    $update = $conn->prepare("UPDATE user_profile SET talent_category = ?, bio = ? WHERE user_id = ?");
    $update->execute([$new_category, $new_bio, $user_id]);

    $message = "Profile updated successfully!";
    $category = $new_category;
    $bio = $new_bio;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
</head>
<body>
    <h2>Edit Profile</h2>

    <?php if ($message): ?>
        <p style="color:green;"><?= $message ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Talent Category:</label>
        <select name="category">
            <option value="Music" <?= $category == 'Music' ? 'selected' : '' ?>>Music</option>
            <option value="Tech" <?= $category == 'Tech' ? 'selected' : '' ?>>Tech</option>
            <option value="Art" <?= $category == 'Art' ? 'selected' : '' ?>>Art</option>
            <option value="Writing" <?= $category == 'Writing' ? 'selected' : '' ?>>Writing</option>
        </select><br><br>

        <label>Bio:</label><br>
        <textarea name="bio" rows="5" cols="40"><?= htmlspecialchars($bio) ?></textarea><br><br>

        <button type="submit">Save Changes</button>
    </form>

    <br>
    <a href="profile.php">← Back to Profile</a>
</body>
</html>
