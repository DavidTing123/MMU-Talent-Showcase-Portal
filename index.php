<?php

// Database connection
$host = 'localhost';
$dbname = 'talent_portal';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT * FROM portfolio ORDER BY upload_date DESC");
    $stmt->execute();
    $talents = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Dummy data for testing (you can remove this later)
    for ($i = 1; $i <= 10; $i++) {
        $talents[] = [
            'file_path' => '',
            'category' => 'Test',
            'dummy' => "Dummy $i"
        ];
    }
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MMU Talent Showcase</title>
    <link rel="stylesheet" href="login/css/index.css">

</head>
<body>

<header class="navbar">
    <div class="logo">MMU Talent</div>
    <nav>
        <ul>
            <li><a href="login/registration.php">Catalogue</a></li>
            <li><a href="login/registration.php">Forum</a></li>
            <li><a href="login/registration.php">FAQs</a></li>
            <li><a href="login/registration.php">Profile</a></li>
            <li><a href="login/registration.php">Sign in</a></li>
        </ul>
    </nav>
</header>

<section class="banner">
    <h1>Showcase Your Talent <br> at MMU!</h1>
</section>

</section>

<section class="trending">
    <h2>Trending Talents</h2>

    <div class="carousel-container">
        <div class="carousel-wrapper">
            <button class="arrow left" onclick="scrollLeft()">&#10094;</button>

            <div class="carousel" id="carousel">
                <?php foreach ($talents as $talent): ?>
                    <div class="talent-box" data-category="<?php echo htmlspecialchars($talent['category']); ?>">
                        <?php
                        if (!empty($talent['file_path'])) {
                            $filePath = htmlspecialchars($talent['file_path']);
                            $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

                            if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
                                echo "<img src='{$filePath}' alt='Portfolio Image' class='portfolio-image'>";
                            } elseif ($fileExtension === 'pdf') {
                                echo "<a href='{$filePath}' target='_blank'>View PDF</a>";
                            } elseif (in_array($fileExtension, ['mp4', 'webm'])) {
                                echo "<video controls src='{$filePath}' class='portfolio-video'></video>";
                            } else {
                                echo "<a href='{$filePath}' target='_blank'>Download File</a>";
                            }
                        } else {
                            echo "<p>{$talent['dummy']}</p>";
                        }
                        ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <button class="arrow right" onclick="scrollRight()">&#10095;</button>
        </div>
    </div>
</section>

<div class="join-button">
    <button>Join Now</button>
</div>

<script src="login/js/index.js"></script>
</body>
</html>
