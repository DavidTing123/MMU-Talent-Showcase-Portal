<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Catalogue</title>
    <link rel="stylesheet" href="css/catalogue.css"> <!-- External CSS -->
</head>
<body>

<!-- Header -->
<header class="navbar">
    <!-- Left: Logo -->
    <a href="index.php" class="logo">
        <img src="image/mmu-logo.png" alt="MMU Logo">
    </a>

    <!-- Center: Catalogue Title -->
    <div class="title">Catalogue</div>

    <!-- Right: Search + Profile -->
    <div class="nav-right">
        <input type="text" class="search-bar" placeholder="Search">
        <a href="profile.php"><img src="image/profile-icon.png" alt="Profile" class="profile-icon"></a>
    </div>
</header>

<!-- Navigation Links below title -->
<nav class="category-nav">
    <a href="#music">Music</a>
    <a href="#technology">Technology</a>
    <a href="#art">Art</a>
</nav>

<!-- Banner -->
<div class="banner">
    <img src="image/talent-banner.png" alt="Banner">
    <div class="banner-text">DISCOVER ALL OUR TALENTS</div>
</div>

<!-- Music Section -->
<section id="music" class="section">
    <h2>Music</h2>
    <div class="card-container">
        <?php for ($i = 1; $i <= 8; $i++): ?>
            <div class="card">
                <img src="music-icon.png" alt="Song <?= $i ?>">
                <p><strong>Song <?= $i ?></strong><br>Description</p>
            </div>
        <?php endfor; ?>
    </div>
</section>

<!-- Technology Section -->
<section id="technology" class="section">
    <h2>Technology</h2>
    <div class="card-container">
        <?php for ($i = 1; $i <= 7; $i++): ?>
            <div class="card">
                <img src="music-icon.png" alt="Video <?= $i ?>">
                <p><strong>Video <?= $i ?></strong><br>Description</p>
            </div>
        <?php endfor; ?>
    </div>
</section>

<!-- Art Section -->
<section id="art" class="section">
    <h2>Art</h2>
    <div class="card-container">
        <?php for ($i = 1; $i <= 6; $i++): ?>
            <div class="card">
                <img src="music-icon.png" alt="Art <?= $i ?>">
                <p><strong>Art <?= $i ?></strong><br>Description</p>
            </div>
        <?php endfor; ?>
    </div>
</section>

</body>
</html>
