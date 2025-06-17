<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>MMU Talent Showcase Portal</title>
</head>
<body>
    <?php if (isset($_SESSION["user_id"])): ?>
        <!-- 登入後介面 -->
        <h2>Welcome back, <?= htmlspecialchars($_SESSION["role"]) ?>!</h2>
        <p><a href="profile.php">Go to your profile</a></p>
        <p><a href="logout.php">Logout</a></p>

    <?php else: ?>
        <!-- 未登入介面 -->
        <h1>Welcome to MMU Talent Showcase Portal</h1>
        <p>Show your talents. Connect with others. Get hired.</p>

        <a href="registration.php">
            <button style="padding: 10px 20px;">Get Start</button>
        </a>
    <?php endif; ?>
</body>
</html>
