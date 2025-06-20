<?php
require_once "db_connect.php"; // Connect to the database using PDO

// Fetch all portfolio entries from all users, sorted alphabetically by title
$stmt = $conn->prepare("SELECT * FROM portfolio ORDER BY title ASC");
$stmt->execute();
$all_works = $stmt->fetchAll();

// Group the works into categories
$categories = [
    "Music" => [],
    "Technology" => [],
    "Art" => [],
    "Writing" => []
];

// Distribute each portfolio item into the right category (case-insensitive)
foreach ($all_works as $work) {
    $cat = ucfirst(strtolower($work["category"])); // e.g., "music" → "Music"
    if (array_key_exists($cat, $categories)) {
        $categories[$cat][] = $work;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Catalogue</title>
    <link rel="stylesheet" href="css/catalogue.css"> <!-- Link to external stylesheet -->
</head>
<body>

<!-- Header with Logo, Title, and Profile Icon -->
<header class="navbar">
    <a href="index.php" class="logo">
        <img src="image/mmu-logo.png" alt="MMU Logo">
    </a>
    <div class="title">Catalogue</div>
    <div class="nav-right">
        <input type="text" class="search-bar" placeholder="Search">
        <a href="profile.php"><img src="image/profile-icon.png" alt="Profile" class="profile-icon"></a>
    </div>
</header>

<!-- Category Navigation (anchors to scroll to section) -->
<nav class="category-nav">
    <a href="#music">Music</a>
    <a href="#technology">Technology</a>
    <a href="#art">Art</a>
    <a href="#writing">Writing</a>
</nav>

<!-- Banner section with overlay text -->
<div class="banner">
    <img src="image/talent-banner.png" alt="Banner">
    <div class="banner-text">DISCOVER ALL OUR TALENTS</div>
</div>

<!-- Loop through each category and display relevant portfolio items -->
<?php foreach ($categories as $categoryName => $items): ?>
<section id="<?= strtolower($categoryName) ?>" class="section">
    <h2><?= htmlspecialchars($categoryName) ?></h2>
    <div class="card-container">

        <?php foreach ($items as $item): ?>
        <div class="card">
            <!-- Display title and category -->
            <strong><?= htmlspecialchars($item["title"]) ?></strong><br>
            <em><?= htmlspecialchars($item["category"]) ?></em><br><br>

            <?php
                // Get file path and type
                $filePath = htmlspecialchars($item["file_path"]);
                $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            ?>

            <?php if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                <!-- Display image file -->
                <img src="<?= $filePath ?>" alt="<?= htmlspecialchars($item["title"]) ?>" class="portfolio-image" style="width:100%; height:auto;">

            <?php elseif ($fileExtension === 'mp4' || $fileExtension === 'webm'): ?>
                <!-- Video preview with controls -->
                <video width="100%" controls 
                    onloadedmetadata="this.currentTime = 0;"
                    ontimeupdate="if(this.currentTime > 10){ this.pause(); }"
                    class="portfolio-video">
                    <source src="<?= $filePath ?>" type="video/<?= $fileExtension ?>">
                    Your browser does not support the video tag.
                </video><br>
                
                <!-- Form to redirect to watch_video.php for full video -->
                <form method="get" action="watch_video.php">
                    <input type="hidden" name="video" value="<?= $filePath ?>">
                    <button type="submit">Watch Full Video</button>
                </form>

            <?php elseif ($fileExtension === 'pdf'): ?>
                <!-- PDF file link -->
                <a href="<?= $filePath ?>" target="_blank" class="file-link">View PDF</a>

            <?php else: ?>
                <!-- For other file types -->
                <a href="<?= $filePath ?>" target="_blank" class="file-link">Download File</a>
            <?php endif; ?>
            
            <!-- Display description if available -->
            <?php if (!empty($item["description"])): ?>
                <div class="description">
                    <?= htmlspecialchars($item["description"]) ?>
                </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>

    </div>
</section>
<?php endforeach; ?>

</body>
</html>