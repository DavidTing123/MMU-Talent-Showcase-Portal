<?php
// auth.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");  // 調整相對路徑
    exit;
}

