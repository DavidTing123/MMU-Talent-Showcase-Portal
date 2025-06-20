<?php
$host = 'localhost';
$dbname = 'talent_portal';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - MMU Talent</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>

<header class="navbar">
    <div class="logo">MMU Talent</div>
    <nav>
        <ul>
            <li><a href="catalogue.php">Users</a></li>
            <li><a href="forum.php">Announcements</a></li>
            <li><a href="faq.php">FAQs</a></li>
            <li><a href="profile.php">&#128100;</a></li>
            <li><a href="#">&#9776;</a></li>
        </ul>
    </nav>
</header>

<section class="banner">
    <h1>Welcome Admin!</h1>
</section>

<div class="tab-buttons">
    <button class="tab-btn active" data-tab="users">Users</button>
    <button class="tab-btn" data-tab="portfolios">Portfolios</button>
    <button class="tab-btn" data-tab="reports">Reports</button>
</div>

<div class="tab-content" id="users">
    <table>
        <tr><th>No.</th><th>Name</th><th>Email</th><th>Category</th><th>Action</th></tr>
        <?php
        $stmt = $pdo->query("SELECT * FROM users");
        $no = 1;
        while ($row = $stmt->fetch()) {
            echo "<tr>
                    <td>{$no}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['category']}</td>
                    <td><button class='danger-btn'>Remove</button></td>
                </tr>";
            $no++;
        }
        ?>
    </table>
</div>

<div class="tab-content hidden" id="portfolios">
    <table>
        <tr><th>No.</th><th>Portfolio Title</th><th>Author</th><th>Approval</th></tr>
        <?php
        $stmt = $pdo->query("
            SELECT p.title, u.name AS author
            FROM portfolio p
            LEFT JOIN users u ON p.user_id = u.id
        ");
        $no = 1;
        while ($row = $stmt->fetch()) {
            echo "<tr>
                    <td>{$no}</td>
                    <td>{$row['title']}</td>
                    <td>{$row['author']}</td>
                    <td>
                        <button class='success-btn'>Approve</button>
                        <button class='danger-btn'>Reject</button>
                    </td>
                </tr>";
            $no++;
        }
        ?>
    </table>
</div>

<div class="tab-content hidden" id="reports">
    <p style="text-align:center;">No reports yet.</p>
</div>

<script src="js/admin.js"></script>
</body>
</html>
