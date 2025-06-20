<?php
require_once "db_connect.php";

// 讀取所有作品，依標題排序
$stmt = $conn->prepare("SELECT * FROM portfolio ORDER BY title ASC");
$stmt->execute();
$all_works = $stmt->fetchAll();

// 分類初始化
$categories = [
    "Music" => [],
    "Technology" => [],
    "Art" => [],
    "Writing" => []
];

// 分配作品進入對應分類
foreach ($all_works as $work) {
    $cat = ucfirst(strtolower($work["category"]));
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
    <link rel="stylesheet" href="css/catalogue.css">
</head>
<body>

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

<nav class="category-nav">
    <a href="#music">Music</a>
    <a href="#technology">Technology</a>
    <a href="#art">Art</a>
    <a href="#writing">Writing</a>
</nav>

<div class="banner">
    <img src="image/talent-banner.png" alt="Banner">
    <div class="banner-text">DISCOVER ALL OUR TALENTS</div>
</div>

<?php foreach ($categories as $categoryName => $items): ?>
<section id="<?= strtolower($categoryName) ?>" class="section">
    <h2><?= htmlspecialchars($categoryName) ?></h2>
    <div class="card-container">

        <?php foreach ($items as $item): ?>
            <?php
                $filePath = $item["file_path"];
                $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            ?>
            <div class="card">
                <a href="view_portfolio.php?id=<?= $item["portfolio_id"] ?>&back=catalogue.php" style="text-decoration: none; color: inherit;">
                    <?php if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                        <img src="<?= $filePath ?>" alt="<?= htmlspecialchars($item["title"]) ?>" style="width:100%; border-radius:8px;">
                    <?php elseif ($fileExtension === 'mp4'): ?>
                        <video style="width:100%; border-radius:8px;" muted>
                            <source src="<?= $filePath ?>" type="video/mp4">
                        </video>
                    <?php endif; ?>

                    <strong><?= htmlspecialchars($item["title"]) ?></strong><br>
                    <!-- <em><= htmlspecialchars($item["category"]) ?></em><br><br> -->

                    

                    <?php if (!empty($item["price"])): ?>
                        <div class="price">
                            <p style="font-weight:bold; color:#2b7a0b;">Price: RM <?= number_format($item["price"], 2) ?></p>
                        </div>
                    <?php endif; ?>

                    
                </a>
            </div>
        <?php endforeach; ?>

    </div>
</section>
<?php endforeach; ?>

</body>
</html>
