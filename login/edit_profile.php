<?php
require_once "auth.php";
require_once "db_connect.php";

$user_id = $_SESSION["user_id"];
$message = "";
$name = "";
$category = "";
$bio = "";

// 讀取目前資料
$stmt = $conn->prepare("SELECT name, talent_category, bio FROM user_profile WHERE user_id = ?");
$stmt->execute([$user_id]);
$data = $stmt->fetch();

if ($data) {
    $name = $data["name"];
    $category = $data["talent_category"];
    $bio = $data["bio"];
}

// 處理更新請求
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_name = htmlspecialchars(trim($_POST["name"]));
    $new_category = $_POST["category"];
    $new_bio = htmlspecialchars(trim($_POST["bio"]));

    $stmt = $conn->prepare("UPDATE user_profile SET name = ?, talent_category = ?, bio = ? WHERE user_id = ?");
    $stmt->execute([$new_name, $new_category, $new_bio, $user_id]);

    $message = "✅ Profile updated successfully!";
    $name = $new_name;
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
    <h2>Edit Profile Info</h2>

    <?php if ($message): ?>
        <p style="color:green;"><?= $message ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Name:</label><br>
        <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" required><br><br>

        <label>Talent Category:</label><br>
        <select name="category">
            <option value="Music" <?= $category == "Music" ? "selected" : "" ?>>Music</option>
            <option value="Tech" <?= $category == "Tech" ? "selected" : "" ?>>Tech</option>
            <option value="Art" <?= $category == "Art" ? "selected" : "" ?>>Art</option>
            <option value="Writing" <?= $category == "Writing" ? "selected" : "" ?>>Writing</option>
        </select><br><br>

        <label>Bio:</label><br>
        <textarea name="bio" rows="5" cols="40"><?= htmlspecialchars($bio) ?></textarea><br><br>

        <button type="submit">Save Changes</button>
    </form>

    <br>
    <a href="profile.php">← Back to Profile</a>
</body>
</html>
