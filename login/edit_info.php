<?php
require_once "auth.php";
require_once "db_connect.php";

$user_id = $_SESSION["user_id"];
$message = "";
$name = "";
$category = "";

// 讀取資料
$stmt = $conn->prepare("SELECT name, talent_category FROM user_profile WHERE user_id = ?");
$stmt->execute([$user_id]);
$data = $stmt->fetch();

if ($data) {
    $name = $data["name"];
    $category = $data["talent_category"];
}

// 更新請求
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_name = htmlspecialchars(trim($_POST["name"]));
    $new_category = $_POST["category"];

    $stmt = $conn->prepare("UPDATE user_profile SET name = ?, talent_category = ? WHERE user_id = ?");
    $stmt->execute([$new_name, $new_category, $user_id]);

    $message = "✅ Info updated successfully!";
    $name = $new_name;
    $category = $new_category;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Info</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Edit Name & Category</h2>

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

        <button type="submit">Save Changes</button>
    </form>

    <br>
    <a href="profile.php">← Back to Profile</a>
</body>
</html>
