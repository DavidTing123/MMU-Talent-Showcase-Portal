<?php
require_once "db_connect.php";

// Get topic ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch topic
$stmt = $conn->prepare("SELECT * FROM forum_topics WHERE id = ?");
$stmt->execute([$id]);
$topic = $stmt->fetch(PDO::FETCH_ASSOC);

// If not found
if (!$topic) {
    echo "<h2>Topic not found.</h2>";
    exit;
}

// Handle new comment submission
$commentError = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_comment'])) {
    $commenter = trim($_POST['commenter']);
    $commentText = trim($_POST['comment']);

    if ($commenter && $commentText) {
        $stmt = $conn->prepare("INSERT INTO forum_comments (topic_id, commenter_name, comment) VALUES (?, ?, ?)");
        $stmt->execute([$id, $commenter, $commentText]);
        header("Location: view.php?id=$id"); // Prevent resubmission
        exit;
    } else {
        $commentError = "Please enter your name and comment.";
    }
}

// Fetch all comments for this topic
$stmt = $conn->prepare("SELECT * FROM forum_comments WHERE topic_id = ? ORDER BY created_at DESC");
$stmt->execute([$id]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($topic['main_title']) ?> - Topic Detail</title>
    <link rel="stylesheet" href="css/forum.css">
    <style>
        .view-container {
            max-width: 800px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }
        .media-preview {
            margin-top: 20px;
        }
        .back-link {
            text-decoration: none;
            display: inline-block;
            margin-bottom: 15px;
            color: #007BFF;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .comment-section {
            margin-top: 40px;
        }
        .comment-form {
            display: none;
            margin-top: 20px;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 8px;
        }
        .comment-form input, .comment-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        .comment-form button {
            background-color: #c5baa7;
            color: black;
            padding: 8px 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        .comment {
            background: #f2f2f2;
            border-radius: 8px;
            padding: 12px;
            margin-top: 15px;
        }
        .comment .meta {
            font-size: 13px;
            color: #555;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>

<div class="view-container">
    <a class="back-link" href="forum.php">&larr; Back to Forum</a>

    <h2><?= htmlspecialchars($topic['main_title']) ?></h2>
    <p><strong>Name:</strong> <?= htmlspecialchars($topic['name']) ?></p>
    <p><strong>Topic:</strong> <?= htmlspecialchars($topic['topic_category']) ?></p>
    <p><strong>Date:</strong> <?= htmlspecialchars($topic['created_at']) ?></p>
    <p><?= nl2br(htmlspecialchars($topic['description'])) ?></p>

    <!-- Media Preview -->
    <?php if (!empty($topic['media_path']) && file_exists($topic['media_path'])): ?>
        <div class="media-preview">
            <?php
            $ext = strtolower(pathinfo($topic['media_path'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])):
            ?>
                <img src="<?= htmlspecialchars($topic['media_path']) ?>" style="max-width:100%; border-radius:8px;">
            <?php elseif ($ext === 'mp4'): ?>
                <video controls style="max-width:100%; border-radius:8px;">
                    <source src="<?= htmlspecialchars($topic['media_path']) ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Comments Section -->
    <div class="comment-section">
        <h3>Comments</h3>

        <button onclick="document.querySelector('.comment-form').style.display='block'">+ Add Comment</button>

        <form class="comment-form" method="POST">
            <input type="hidden" name="add_comment" value="1">
            <input type="text" name="commenter" placeholder="Your name" required>
            <textarea name="comment" placeholder="Write your comment..." required></textarea>
            <button type="submit">Submit</button>
            <?php if ($commentError): ?>
                <p style="color:red;"><?= $commentError ?></p>
            <?php endif; ?>
        </form>

        <!-- Display Comments -->
        <?php if ($comments): ?>
            <?php foreach ($comments as $c): ?>
                <div class="comment">
                    <div class="meta"><?= htmlspecialchars($c['commenter_name']) ?> | <?= $c['created_at'] ?></div>
                    <div><?= nl2br(htmlspecialchars($c['comment'])) ?></div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No comments yet. Be the first to comment!</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
