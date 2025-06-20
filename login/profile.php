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

// ✅ 初始化作品集
$portfolios = [];
$stmt = $conn->prepare("SELECT * FROM portfolio WHERE user_id = ?");
$stmt->execute([$user_id]);
$portfolios = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
    <link rel="stylesheet" href="css/profile.css">
</head>
<body>
<div class="profile-container">

    <div class="menu-icon" onclick="document.querySelector('.menu-popup').classList.toggle('active')">
        ☰
    </div>

    <div class="menu-popup">
        <a href="index.php">Home</a>
        <a href="catalogue.php">Catalogue</a>
        <a href="forum.php">Forum</a>
        <a href="FAQs.php">FAQs</a>
        <a href="registration.php">Sign Up</a>
        <a href="shoppingcart.php">Shoping</a>
    </div>

    <h2>My Profile</h2>

    <?php if ($message): ?>
        <p style="color:green;"><?= $message ?></p>
    <?php endif; ?>

    <div class="top-section">
        <div class="profile-pic">
            <form method="POST" enctype="multipart/form-data" id="profilePicForm">
                <input type="hidden" name="upload_pic" value="1">
                <input type="file" name="profile_pic" id="profilePicInput" accept="image/*" style="display:none;" onchange="document.getElementById('profilePicForm').submit();">
                <img src="<?= $pic_path ?>" onclick="document.getElementById('profilePicInput').click();">
            </form>
            <a href="edit_bio.php" style="text-decoration:none; color:inherit;">
    <div class="bio-box" title="Click to edit your bio">
        <?= nl2br(htmlspecialchars($bio)) ?>
    </div>
</a>

        </div>

        <div class="info-box">
            <p><strong>Name:</strong> <?= htmlspecialchars($name) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
            <p><strong>Talent Category:</strong> <?= htmlspecialchars($category) ?></p>
            <button class="edit-btn" onclick="location.href='edit_info.php'">Edit Info</button>
            <!-- <button class="edit-btn" onclick="location.href='edit_bio.php'">Edit Bio</button> -->
            <!-- <button class="edit-btn" onclick="location.href='edit_profile.php'">Edit Information</button> -->
        </div>
    </div>

    <div class="portfolio-section">
        <h3>Portfolio(s) <a href="upload_portfolio.php" class="edit-btn" style="font-size:14px;">Upload</a></h3>
<div class="portfolio-list">
    <?php if (count($portfolios) === 0): ?>
        <p style="margin-left: 10px;">You haven't uploaded any portfolio yet.</p>
    <?php else: ?>
        <?php foreach ($portfolios as $p): ?>
            <?php
                $filePath = $p["file_path"];
                $fileType = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            ?>
            <div class="portfolio-item">
                <strong><?= htmlspecialchars($p["title"]) ?></strong><br>

                <a href="view_portfolio.php?id=<?= $p["portfolio_id"] ?>&back=profile.php">
                    <?php if (in_array($fileType, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                        <img src="<?= $filePath ?>" style="max-width: 100%; height: auto; border-radius: 8px;">
                    <?php elseif ($fileType === 'mp4'): ?>
                        <video style="width: 100%; border-radius: 8px;" muted>
                            <source src="<?= $filePath ?>" type="video/mp4">
                        </video>
                    <?php endif; ?>
                </a>


                <a href="edit_portfolio.php?id=<?= $p["portfolio_id"] ?>" style="font-size: 14px; color: blue;">✏️ Edit</a>

                <form method="POST" style="margin-top:5px;">
                    <input type="hidden" name="delete_id" value="<?= $p["portfolio_id"] ?>">
                    <button type="submit" onclick="return confirm('Are you sure you want to delete this work?')">Delete</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    </div>

    <br><a href="logout.php">Logout</a>
</div>
<script>
function openFullscreen(imgElement) {
    if (imgElement.requestFullscreen) {
        imgElement.requestFullscreen();
    } else if (imgElement.webkitRequestFullscreen) {
        imgElement.webkitRequestFullscreen();
    } else if (imgElement.msRequestFullscreen) {
        imgElement.msRequestFullscreen();
    }
}
</script>

</body>
</html>


