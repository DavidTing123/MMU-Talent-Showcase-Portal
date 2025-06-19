<!-- login.php -->
<?php
session_start();
require_once "db_connect.php";
// if ($user && password_verify($password, $user["password_hash"])) {
//     $_SESSION["user_id"] = $user["user_id"];
//     $_SESSION["role"] = $user["role"];

//     if ($_SESSION["role"] === "admin") {
//         header("Location: admin.php");
//     } else {
//         header("Location: profile.php");
//     }
//     exit;
// }


$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars(trim($_POST["email"]));
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user["password_hash"])) {
        $_SESSION["user_id"] = $user["user_id"];
        $_SESSION["role"] = $user["role"];
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">

</head>
<body>
    <h2>User Login</h2>
    <form method="POST">
        Email: <input type="email" name="email" required><br><br>
        Password: <input type="password" name="password" required><br><br>
        <button type="submit">Login</button>
    </form>
    <p style="color:red;"><?= $error ?></p>
    <!-- ✅ 新增註冊連結 -->
    <p>Don't have an account? <a href="registration.php">Sign up here</a></p>
</body>
</html>
