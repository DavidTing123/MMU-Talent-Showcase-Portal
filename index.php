<!-- index.php -->
<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
?>
<h2>Welcome, you are logged in!</h2>
<p>Your role is: <?= $_SESSION["role"] ?></p>
<a href="logout.php">Logout</a>
