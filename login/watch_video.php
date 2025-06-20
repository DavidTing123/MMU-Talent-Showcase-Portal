<?php
$video = isset($_GET["video"]) ? $_GET["video"] : "";
if (!file_exists($video)) {
    echo "Video not found.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Watch Full Video</title>
</head>
<body>
    <h2>Full Video</h2>
    <video width="80%" controls autoplay>
        <source src="<?= htmlspecialchars($video) ?>" type="video/mp4">
    </video>
    <br><br>
    <a href="catalogue.php">← Back to Catalogue</a>
</body>
</html>