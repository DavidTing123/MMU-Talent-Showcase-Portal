<?php
require_once "auth.php";
require_once "db_connect.php";

$user_id = $_SESSION["user_id"];
$email = "";
$name = "";
$category = "";
$bio = "";
$message = "";

// ✅ 上傳大頭貼
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["upload_pic"])) {
    if (isset($_FILES["profile_pic"]) && $_FILES["profile_pic"]["error"] == 0) {
        $pic = $_FILES["profile_pic"];
        $ext = strtolower(pathinfo($pic["name"], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($ext, $allowed)) {
            $filename = "uploads/profile_" . $user_id . "_" . time() . "." . $ext;
            move_uploaded_file($pic["tmp_name"], $filename);

            $stmt = $conn->prepare("UPDATE user_profile SET profile_picture = ? WHERE user_id = ?");
            $stmt->execute([$filename, $user_id]);

            $message = "Profile picture updated successfully!";
        } else {
            $message = "Invalid image type.";
        }
    } else {
        $message = "Upload failed. Error code: " . $_FILES["profile_pic"]["error"];
    }
}

// ✅ 刪除作品
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_id"])) {
    $deleteId = $_POST["delete_id"];
    $stmt = $conn->prepare("SELECT file_path FROM portfolio WHERE portfolio_id = ? AND user_id = ?");
    $stmt->execute([$deleteId, $user_id]);
    $file = $stmt->fetch();

    if ($file) {
        if (file_exists($file["file_path"])) {
            unlink($file["file_path"]);
        }
        $stmt = $conn->prepare("DELETE FROM portfolio WHERE portfolio_id = ? AND user_id = ?");
        $stmt->execute([$deleteId, $user_id]);
        $message = "Portfolio deleted successfully.";
    } else {
        $message = "Unauthorized deletion attempt blocked.";
    }
}

// ✅ 讀取使用者資料
$stmt = $conn->prepare("
    SELECT u.email, p.name, p.talent_category, p.bio, p.profile_picture
    FROM users u
    JOIN user_profile p ON u.user_id = p.user_id
    WHERE u.user_id = ?
");
$stmt->execute([$user_id]);
$data = $stmt->fetch();

if ($data) {
    $email = $data["email"];
    $name = $data["name"];
    $category = $data["talent_category"];
    $bio = $data["bio"];
    $pic_path = (isset($data["profile_picture"]) && file_exists($data["profile_picture"]))
        ? $data["profile_picture"]
        : "default-avatar.png";
}

// // ✅ 更新 bio
// if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["bio"])) {
//     $new_bio = htmlspecialchars(trim($_POST["bio"]));
//     $update = $conn->prepare("UPDATE user_profile SET bio = ? WHERE user_id = ?");
//     $update->execute([$new_bio, $user_id]);
//     $message = "Bio updated successfully.";
//     $bio = $new_bio;
// }
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
    <!-- <link rel="stylesheet" href="css/profile.css"> -->
</head>
<body>
    <h2>Profile</h2>

    <?php if ($message): ?>
        <p style="color:green;"><?= $message ?></p>
    <?php endif; ?>

    <!-- ✅ 點圖片上傳大頭貼 -->
    <form method="POST" enctype="multipart/form-data" id="profilePicForm">
        <input type="hidden" name="upload_pic" value="1">
        <input type="file" name="profile_pic" id="profilePicInput" accept="image/*" style="display:none;" onchange="document.getElementById('profilePicForm').submit();">
        
        <img src="<?= $pic_path ?>" alt="Profile Picture" width="120" height="120"
             style="border-radius:50%; border:2px solid gray; cursor:pointer;"
             onclick="document.getElementById('profilePicInput').click();">
    </form>
    <small>Click the image to update your profile picture</small>

    <hr>

    <!-- ✅ 顯示使用者資訊 -->
    <p><strong>Name:</strong> <?= htmlspecialchars($name) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
    <p><strong>Talent Category:</strong> <?= htmlspecialchars($category) ?></p>
    <p><strong>Bio:</strong> <?= nl2br(htmlspecialchars($bio)) ?></p>


    <!-- ✅ 編輯 Bio -->
    <!-- <h3>Edit Your Bio</h3>
    <form method="POST">
        <textarea name="bio" rows="5" cols="40">
            <= htmlspecialchars($bio) ?>
        </textarea><br><br>
        <button type="submit">Update Bio</button>
    </form> -->
    <a href="edit_profile.php">✏️ Edit Profile Info</a><br>

    <br><hr><br>

    <!-- ✅ 顯示作品 -->
    <h3>My Uploaded Works:</h3>
    <div style="display:flex; flex-wrap:wrap; gap:20px;">
    <?php
    $stmt = $conn->prepare("SELECT * FROM portfolio WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $portfolios = $stmt->fetchAll();

    foreach ($portfolios as $p):
        $filePath = $p["file_path"];
        $fileType = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    ?>
        <div style="border:1px solid #ccc; padding:10px; width:250px;">
            <strong><?= htmlspecialchars($p["title"]) ?></strong><br>
            <em><?= $p["category"] ?></em><br><br>

            <?php if (in_array($fileType, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                <img src="<?= $filePath ?>" style="width:100%; height:auto;"><br>
            <?php elseif ($fileType === 'mp4'): ?>
                <video controls width="100%">
                    <source src="<?= $filePath ?>" type="video/mp4">
                </video><br>
            <?php else: ?>
                <a href="<?= $filePath ?>" target="_blank">Download File</a><br>
            <?php endif; ?>

            <form method="POST" style="margin-top:10px;">
                <input type="hidden" name="delete_id" value="<?= $p["portfolio_id"] ?>">
                <button type="submit" onclick="return confirm('Are you sure you want to delete this work?')">Delete</button>
            </form>
        </div>

    <?php endforeach; ?>
    </div>

    <br><br>
    <a href="upload_portfolio.php">+ Upload New Work</a><br>
    <a href="logout.php">Logout</a>
</body>
</html>
