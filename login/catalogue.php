<?php
require_once "db_connect.php";

// Fetch all portfolio entries from all users, sorted alphabetically by title
$stmt = $conn->prepare("SELECT * FROM portfolio ORDER BY title ASC");
$stmt->execute();
$all_works = $stmt->fetchAll();

// Group the works into categories
$categories = [
    "Music" => [],
    "Tech" => [],
    "Art" => [],
    "Writing" => []
];

// Distribute each portfolio item into the right category
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
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0; top: 0;
            width: 100%; height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.6);
        }
        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 20px;
            width: 80%;
            max-width: 600px;
            border-radius: 8px;
            position: relative;
        }
        .close {
            position: absolute;
            top: 10px; right: 15px;
            font-size: 24px;
            cursor: pointer;
        }
        .description-box {
            max-height: 150px;
            overflow-y: auto;
            padding: 8px;
            border: 1px solid #ddd;
            margin-top: 10px;
            background: #f9f9f9;
            white-space: pre-wrap;
        }
        .price-tag {
            margin-top: 10px;
            font-weight: bold;
            color: green;
        }
        .pdf-preview {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 15px;
        }
        .pdf-preview img {
            width: 60px;
            margin-bottom: 8px;
        }
        .pdf-preview span {
            color: #555;
            font-size: 14px;
        }
    </style>
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
    <a href="#Tech">Technology</a>
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
                $fileExt = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                $thumbnail = isset($item["thumbnail_path"]) && file_exists($item["thumbnail_path"])
                    ? $item["thumbnail_path"]
                    : null;
            ?>
            <div class="card" onclick="showModal(
                '<?= htmlspecialchars($item["title"], ENT_QUOTES) ?>',
                '<?= htmlspecialchars($filePath, ENT_QUOTES) ?>',
                '<?= htmlspecialchars($item["description"] ?? '', ENT_QUOTES) ?>',
                '<?= htmlspecialchars($item["price"] ?? '', ENT_QUOTES) ?>'
            )">
                <?php if ($thumbnail): ?>
                    <img src="<?= $thumbnail ?>" style="width:100%; border-radius:8px;">
                <?php elseif (in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                    <img src="<?= $filePath ?>" style="width:100%; border-radius:8px;">
                <?php elseif ($fileExt === 'mp4'): ?>
                    <video style="width:100%; border-radius:8px;" muted>
                        <source src="<?= $filePath ?>" type="video/mp4">
                    </video>
                <?php elseif ($fileExt === 'mp3'): ?>
                    <div style="text-align:center; padding:10px;">
                        <img src="https://cdn-icons-png.flaticon.com/512/727/727245.png" width="60" alt="MP3 Icon">
                        <div style="font-size:14px;">MP3 Audio</div>
                    </div>
                <?php elseif ($fileExt === 'pdf'): ?>
                    <div class="pdf-preview" style="text-align:center;">
                        <img src="https://cdn-icons-png.flaticon.com/512/337/337946.png" width="50" alt="PDF Icon">
                        <div style="font-size:14px;">PDF Preview</div>
                    </div>
                <?php endif; ?>
                <strong><?= htmlspecialchars($item["title"]) ?></strong><br>
                <em><?= htmlspecialchars($item["category"]) ?></em><br>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endforeach; ?>

<div id="modal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="hideModal()">&times;</span>
        <div id="modal-media"></div>
        <h3 id="modal-title"></h3>
        <div id="modal-description" class="description-box"></div>
        <div id="modal-price" class="price-tag"></div>
    </div>
</div>

<script>
function showModal(title, filePath, description, price) {
    const ext = filePath.split('.').pop().toLowerCase();
    const mediaContainer = document.getElementById("modal-media");
    mediaContainer.innerHTML = "";

    if (["jpg", "jpeg", "png", "gif"].includes(ext)) {
        mediaContainer.innerHTML = `<img src="${filePath}" style="width:100%; border-radius:8px;">`;
    } else if (ext === "mp4") {
        mediaContainer.innerHTML = `
            <video controls autoplay style="width:100%; border-radius:8px;">
                <source src="${filePath}" type="video/mp4">
            </video>`;
    } else if (ext === "mp3") {
        mediaContainer.innerHTML = `
            <audio controls autoplay style="width:100%;">
                <source src="${filePath}" type="audio/mpeg">
            </audio>`;
    } else if (ext === "pdf") {
        mediaContainer.innerHTML = `
            <iframe src="${filePath}" width="100%" height="500px" style="border:1px solid #ccc; border-radius: 8px;"></iframe>`;
    } else {
        mediaContainer.innerHTML = `<p>Cannot preview this file type.</p>`;
    }

    document.getElementById("modal-title").textContent = title;
    document.getElementById("modal-description").textContent = description || "No description.";
    document.getElementById("modal-price").textContent = price ? `Price: RM ${parseFloat(price).toFixed(2)}` : "";

    document.getElementById("modal").style.display = "block";
}

function hideModal() {
    document.getElementById("modal").style.display = "none";
}
</script>

</body>
</html>
