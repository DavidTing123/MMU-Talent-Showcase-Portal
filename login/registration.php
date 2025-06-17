<?php
require_once "db_connect.php"; // 資料庫連線
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST["name"]));
    $email = htmlspecialchars(trim($_POST["email"]));
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $role = 'student'; // 預設為學生
    $category = $_POST["category"];

    // 驗證格式
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
    } elseif ($password !== $confirm_password) {
        $message = "Passwords do not match.";
    } else {
        // 檢查是否已有此帳號
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $message = "Email already registered.";
        } else {
            // 儲存使用者帳號
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (email, password_hash, role) VALUES (?, ?, ?)");
            $stmt->execute([$email, $hashed_password, $role]);

            // 取得新註冊的 user_id
            $userId = $conn->lastInsertId();

            // 儲存才藝分類到 user_profile 表
            $stmt2 = $conn->prepare("INSERT INTO user_profile (user_id, name, talent_category) VALUES (?, ?, ?)");
            $stmt2->execute([$userId, $name, $category]);


            // 導向登入頁
            header("Location: login.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>
    <h2>User Registration</h2>
    <form method="POST">
        Name: <input type="text" name="name" required><br><br>

        Email: <input type="email" name="email" required><br><br>

        Password: <input type="password" name="password" required><br><br>

        Confirm Password: <input type="password" name="confirm_password" required><br><br>

        Talent Category:
        <select name="category">
            <option value="Music">Music</option>
            <option value="Tech">Tech</option>
            <option value="Art">Art</option>
            <option value="Writing">Writing</option>
        </select><br><br>

        <button type="submit">Register</button>
        <p>Not a new user? <a href="login.php">Login here</a></p>
    </form>

    <?php if ($message): ?>
        <p style="color:red;"><?= $message ?></p>

    <?php endif; ?>
</body>
</html>
