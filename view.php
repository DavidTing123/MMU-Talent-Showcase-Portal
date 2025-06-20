<?php
// view.php - Show full description of a selected topic

// Initialize topics array
$topics = [];

// Check if data.json exists and decode it into $topics array
if (file_exists('data.json')) {
    $topics = json_decode(file_get_contents('data.json'), true) ?? [];
}

// Get the topic ID from the URL query string, or use -1 if not set
$id = isset($_GET['id']) ? (int)$_GET['id'] : -1;

// Check if the topic with this ID exists
if (!isset($topics[$id])) {
    echo "<h2>Topic not found.</h2>";
    exit(); // Stop further processing
}

// Get the selected topic by ID
$topic = $topics[$id];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($topic['mainTitle']) ?> - Full View</title>
    <!-- Link to the same CSS as forum page -->
    <link rel="stylesheet" href="css/forum.css">

    <!-- Extra internal styles specific to this page -->
    <style>
        .view-container {
            max-width: 800px; /* Limit width for readability */
            margin: 50px auto; /* Center and add top/bottom spacing */
            background: #fff; /* White background for content box */
            padding: 30px; /* Inner spacing */
            border-radius: 12px; /* Rounded corners */
            box-shadow: 0 0 10px rgba(0,0,0,0.1); /* Soft shadow around box */
        }

        .view-container h2 {
            margin-bottom: 10px; /* Space below heading */
        }

        .view-meta {
            font-size: 16px; /* Text size for meta info */
            margin-bottom: 20px; /* Space before description */
            color: #555; /* Slightly muted color */
        }

        .back-link {
            display: inline-block; /* Let it behave like a button */
            margin: 20px 0; /* Space above and below */
            color: #333; /* Dark text */
            text-decoration: none; /* No underline */
            font-weight: bold; /* Bold text */
        }

        .back-link:hover {
            text-decoration: underline; /* Underline on hover */
        }

        .media-container {
            margin-top: 20px; /* Space above media */
        }

        .media-container img, .media-container video {
            max-width: 100%; /* Responsive width */
            border-radius: 8px; /* Rounded media */
        }
    </style>
</head>
<body>
    <!-- Main container for viewing topic -->
    <div class="view-container">

        <!-- Link to go back to forum.php -->
        <a class="back-link" href="forum.php">&larr; Back to Forum</a>

        <!-- Show the title of the topic -->
        <h2><?= htmlspecialchars($topic['mainTitle']) ?></h2>

        <!-- Show author's name and topic category -->
        <div class="view-meta">
            <strong>Name:</strong> <?= htmlspecialchars($topic['name']) ?><br>
            <strong>Topic:</strong> <?= htmlspecialchars($topic['topic']) ?>
        </div>

        <hr>

        <!-- Show description of the topic with newlines preserved -->
        <p><?= nl2br(htmlspecialchars($topic['desc'])) ?></p>

        <!-- If media exists, show it -->
        <?php if (!empty($topic['media'])): ?>
            <div class="media-container">
                <!-- Show image if it's an image file -->
                <?php if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $topic['media'])): ?>
                    <img src="<?= htmlspecialchars($topic['media']) ?>" alt="Uploaded Image">
                <!-- Show video if it's a video file -->
                <?php elseif (preg_match('/\.(mp4|webm|ogg)$/i', $topic['media'])): ?>
                    <video controls>
                        <source src="<?= htmlspecialchars($topic['media']) ?>">
                        Your browser does not support the video tag.
                    </video>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

