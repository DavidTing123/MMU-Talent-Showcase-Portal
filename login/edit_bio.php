<?php
require_once "auth.php";
require_once "db_connect.php";

$user_id = $_SESSION["user_id"];
$message = "";
$bio = "";

// 讀取 bio
$stmt = $conn->prepare("SELECT bio FROM user_profile WHERE user_id = ?");
$stmt->execute([$user_id]);
$data = $stmt->fetch();

if ($data) {
    $bio = $data["bio"];
}

// 更新 bio
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_bio = htmlspecialchars(trim($_POST["bio"]));
    $stmt = $conn->prepare("UPDATE user_profile SET bio = ? WHERE user_id = ?");
    $stmt->execute([$new_bio, $user_id]);
    $message = "✅ Bio updated successfully!";
    $bio = $new_bio;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Bio</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Edit Your Bio</h2>

    <?php if ($message): ?>
        <p style="color:green;"><?= $message ?></p>
    <?php endif; ?>

    <form method="POST">
        <textarea name="bio" rows="5" cols="50"><?= htmlspecialchars($bio) ?></textarea><br><br>
        <button type="submit">Update Bio</button>
    </form>

    <br>
    <a href="profile.php">← Back to Profile</a>
</body>
</html>
