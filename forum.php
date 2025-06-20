<?php
// forum.php - Forum page to view and submit topics

// Initialize an empty array to store topics
$topics = [];

// If data.json exists, load and decode it into $topics array
if (file_exists("data.json")) {
    $topics = json_decode(file_get_contents("data.json"), true) ?? []; // If json_decode fails, fallback to empty array
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get all the input values from POST request
    $name = trim($_POST['name']); // Username
    $mainTitle = trim($_POST['mainTitle']); // Title of the post
    $desc = trim($_POST['desc']); // Description of the post
    $topic = trim($_POST['topic']); // Topic category chosen from dropdown

    $validTopics = ["Writing", "Music", "Technology", "Art"]; // Pre-defined acceptable categories

    $mediaFile = null; // Initialize media path as null

    // If a media file was uploaded, handle file saving
    if (!empty($_FILES['media']['name'])) {
        $mediaDir = "uploads/"; // Directory to store uploads
        if (!is_dir($mediaDir)) mkdir($mediaDir); // Create folder if not exist
        $fileName = basename($_FILES["media"]["name"]); // Get the file name only
        $targetPath = $mediaDir . time() . "_" . $fileName; // Create unique file path with timestamp
        move_uploaded_file($_FILES["media"]["tmp_name"], $targetPath); // Move the uploaded file to server
        $mediaFile = $targetPath; // Store file path
    }

    // Only save if all required fields are filled correctly
    if ($name && $mainTitle && $desc && in_array($topic, $validTopics)) {
        // Add the new topic as an associative array
        $topics[] = [
            'name' => $name,
            'mainTitle' => $mainTitle,
            'desc' => $desc,
            'topic' => $topic,
            'media' => $mediaFile // Store media file path if uploaded
        ];
        // Save updated array back to data.json
        file_put_contents("data.json", json_encode($topics));

        // Redirect to avoid form resubmission when page is refreshed
        header("Location: forum.php");
        exit();
    } else {
        // Display error if validation fails
        $error = "Please fill all fields correctly. Only Writing, Music, Technology, or Art allowed.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forum</title>
    <!-- Link to external CSS -->
    <link rel="stylesheet" href="css/forum.css">
</head>
<body>
<header>
    <!-- Logo that links to homepage -->
    <a href="index.php">
        <img src="image/mmu-logo.png" alt="MMU Logo" class="logo">
    </a>

    <!-- Page title -->
    <h1>Forum</h1>

    <!-- Right side of header: search + profile icon -->
    <div class="header-right">
        <input type="text" id="searchInput" placeholder="Search" onkeyup="searchTopics()">
        <a href="profile.php">
            <img src="image/profile-icon.png" alt="Profile" class="profile-icon">
        </a>
    </div>
</header>

<main>
    <h2>Welcome !</h2>

    <!-- Show error message if validation fails -->
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <!-- Add a Topic button (reveals the form when clicked) -->
    <button onclick="showForm()" id="addTopicBtn">Add a Topic</button>

    <!-- Topic submission form (initially hidden) -->
    <form id="topicForm" method="POST" enctype="multipart/form-data" style="display:none; max-width:500px; margin-bottom:30px; padding:20px;">
        <!-- Name input -->
        <input type="text" name="name" placeholder="Enter your name" required><br>
        <!-- Title input -->
        <input type="text" name="mainTitle" placeholder="Enter main title" required><br>
        <!-- Description textarea -->
        <textarea name="desc" placeholder="Enter description" required></textarea><br>
        <!-- Topic dropdown -->
        <select name="topic" required>
            <option value="">Select Topic</option>
            <option value="Writing">Writing</option>
            <option value="Music">Music</option>
            <option value="Technology">Technology</option>
            <option value="Art">Art</option>
        </select><br>
        <!-- Media upload field (optional image/video) -->
        <input type="file" name="media" accept="image/*,video/*"><br>
        <!-- Submit button -->
        <button type="submit">OK</button>
    </form>

    <!-- Display all submitted topics -->
    <div id="topics">
        <?php foreach ($topics as $index => $item): ?>
            <!-- Each card links to view.php to see full post -->
            <div class="card" onclick="location.href='view.php?id=<?= $index ?>'">
                <div class="user-info">
                    <!-- Show username -->
                    <span class="username"><?= htmlspecialchars($item['name']) ?></span>
                    <!-- Show topic category -->
                    <span class="topic"><?= htmlspecialchars($item['topic']) ?></span>
                </div>
                <!-- Show the main title -->
                <div class="title"><?= htmlspecialchars($item['mainTitle']) ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<!-- JavaScript function to filter search results -->
<script>
function searchTopics() {
    const input = document.getElementById("searchInput").value.toLowerCase(); // get lowercase input
    const cards = document.getElementsByClassName("card"); // get all topic cards
    for (let card of cards) {
        const text = card.innerText.toLowerCase(); // compare inner text
        card.style.display = text.includes(input) ? "block" : "none"; // hide or show
    }
}

// JavaScript to show form when Add Topic is clicked
function showForm() {
    document.getElementById("topicForm").style.display = "block"; // show the form
    document.getElementById("addTopicBtn").style.display = "none"; // hide the button
}
</script>

</body>
</html>
