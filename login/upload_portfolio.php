<?php
require_once "auth.php";
require_once "db_connect.php";

$user_id = $_SESSION["user_id"];
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = htmlspecialchars($_POST["title"]);
    $category = $_POST["category"];
    $description = htmlspecialchars($_POST["description"]);
    $price = floatval($_POST["price"]);
    $thumbnailPath = null;

    // 檢查價格是否合法（不能為負）
    if ($price < 0) {
        $message = "❌ Price cannot be negative.";
    } else {
        // 儲存縮圖（如果有）
        if (!empty($_FILES["thumbnail"]["name"])) {
            $thumbExt = strtolower(pathinfo($_FILES["thumbnail"]["name"], PATHINFO_EXTENSION));
            if (in_array($thumbExt, ['jpg', 'jpeg', 'png'])) {
                $thumbnailPath = "uploads/thumb_" . time() . "_" . basename($_FILES["thumbnail"]["name"]);
                move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $thumbnailPath);
            }
        }

        // 主檔案處理
        if (!empty($_FILES["file"]["name"])) {
            $fileName = basename($_FILES["file"]["name"]);
            $targetDir = "uploads/";
            $targetFilePath = $targetDir . time() . "_" . $fileName;
            $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'mp4', 'mp3', 'zip'];
            if (in_array($fileType, $allowedTypes)) {
                if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
                    // 寫入資料庫
                    $stmt = $conn->prepare("INSERT INTO portfolio (user_id, title, file_path, category, description, price, thumbnail_path) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$user_id, $title, $targetFilePath, $category, $description, $price, $thumbnailPath]);

                    $message = "✅ Upload successful!";
                } else {
                    $message = "❌ Error uploading file.";
                }
            } else {
                $message = "❌ Invalid file type.";
            }
        } else {
            $message = "❌ Please select a file.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Portfolio</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Upload Your Talent Work</h2>

    <?php if ($message): ?>
        <p style="color:<?= strpos($message, 'success') !== false ? 'green' : 'red' ?>"><?= $message ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        Title: <input type="text" name="title" required><br><br>

        Talent Category:
        <select name="category">
            <option value="Music">Music</option>
            <option value="Technology">Technology</option>
            <option value="Art">Art</option>
            <option value="Writing">Writing</option>
        </select><br><br>

        File: <input type="file" name="file" accept=".jpg,.jpeg,.png,.gif,.pdf,.mp4,.mp3,.zip" required><br><br>

        Description: <br>
        <textarea name="description" rows="4" cols="50" placeholder="Describe your work..."></textarea><br><br>

        <label>Price (RM):</label><br>
        <input type="number" name="price" step="0.01" min="0" required><br><br>

        Thumbnail (optional): <input type="file" name="thumbnail" accept="image/*"><br><br>

        <button type="submit">Upload</button>
    </form>

    <br>
    <a href="profile.php">← Back to Profile</a>
</body>
</html>
