<?php
// view.php - Display full details of a selected topic

require_once "db_connect.php";  // Connect to the MySQL database

// Get the topic ID from the URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Prepare SQL to fetch topic by ID
$stmt = $conn->prepare("SELECT * FROM forum_topics WHERE id = ?");
$stmt->execute([$id]);

// Fetch the topic row from the result
$topic = $stmt->fetch();

// If topic doesn't exist, show error
if (!$topic) {
    echo "<h2>Topic not found.</h2>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($topic['main_title']) ?> - Full View</title>
    <link rel="stylesheet" href="css/forum.css"> <!-- Reuse forum styles -->
    <style>
        .view-container {
            max-width: 800px;
            margin: 50px auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .view-container h2 {
            margin-bottom: 10px;
        }

        .view-meta {
            font-size: 16px;
            margin-bottom: 20px;
            color: #555;
        }

        .back-link {
            display: inline-block;
            margin: 20px 0;
            color: #333;
            text-decoration: none;
            font-weight: bold;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .media-display {
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <!-- Full container for the topic -->
    <div class="view-container">
        <!-- Link back to forum -->
        <a class="back-link" href="forum.php">&larr; Back to Forum</a>

        <!-- Topic title -->
        <h2><?= htmlspecialchars($topic['main_title']) ?></h2>

        <!-- Metadata: name and category -->
        <div class="view-meta">
            <strong>Name:</strong> <?= htmlspecialchars($topic['name']) ?><br>
            <strong>Topic:</strong> <?= htmlspecialchars($topic['topic_category']) ?><br>
            <strong>Date:</strong> <?= htmlspecialchars($topic['created_at']) ?>
        </div>

        <!-- Topic description -->
        <p><?= nl2br(htmlspecialchars($topic['description'])) ?></p>

        <!-- If media exists, show it -->
        <?php if (!empty($topic['media_path'])): ?>
            <div class="media-display">
                <?php
                // Determine file type from extension
                $ext = strtolower(pathinfo($topic['media_path'], PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                    <!-- Show image -->
                    <img src="<?= $topic['media_path'] ?>" alt="Topic Media" style="max-width:100%; height:auto; border-radius:8px;">
                <?php elseif ($ext === 'mp4' || $ext === 'webm'): ?>
                    <!-- Show video -->
                    <video controls style="max-width:100%; border-radius:8px;">
                        <source src="<?= $topic['media_path'] ?>" type="video/<?= $ext ?>">
                        Your browser does not support the video tag.
                    </video>
                <?php else: ?>
                    <!-- Fallback -->
                    <p><a href="<?= $topic['media_path'] ?>" target="_blank">Download Media</a></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>
