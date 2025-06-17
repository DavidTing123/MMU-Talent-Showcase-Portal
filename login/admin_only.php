<?php
require_once "auth.php"; // 先檢查有登入

if ($_SESSION["role"] !== "admin") {
    header("Location: index.php");
    exit;
}
