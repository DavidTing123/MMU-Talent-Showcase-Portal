
<?php
session_start();

// Remove item if requested
if (isset($_GET['remove'])) {
    $removeId = $_GET['remove'];
    if (isset($_SESSION['cart'][$removeId])) {
        unset($_SESSION['cart'][$removeId]);
    }
    header("Location: shoppingcart.php");
    exit;
}

// Dummy cart session if empty
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [
        1 => ["name" => "Basic TV Installation", "price" => 210.00, "qty" => 1, "image" => "uploads/tool.png"],
        2 => ["name" => "Samsung OLED TV", "price" => 35999.00, "qty" => 1, "image" => "uploads/oled.png"]
    ];
}

$cart = $_SESSION['cart'];
$subtotal = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart - MMU Talent</title>
    <link rel="stylesheet" href="css/shoppingcart.css">
</head>
<body>
<header class="navbar">
    <div class="logo">MMU Talent</div>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="catalogue.php">Catalogue</a></li>
            <li><a href="faq.php">FAQs</a></li>
            <li><a href="profile.php">&#128100;</a></li>
        </ul>
    </nav>
</header>

<div class="cart-layout">
    <div class="cart-items">
        <h3>You have <?php echo count($cart); ?> items in your cart</h3>
        <?php foreach ($cart as $id => $item): 
            $total = $item['price'] * $item['qty'];
            $subtotal += $total;
        ?>
        <div class="cart-item">
            <img src="<?php echo $item['image']; ?>" alt="Product">
            <div class="cart-details">
                <h4><?php echo $item['name']; ?></h4>
                <p>RM <?php echo number_format($item['price'], 2); ?></p>
                <p>Qty: <?php echo $item['qty']; ?></p>
            </div>
            <a class="remove-btn" href="?remove=<?php echo $id; ?>">🗑</a>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="cart-summary">
        <h3>Summary</h3>
        <p>Subtotal: RM <?php echo number_format($subtotal, 2); ?></p>
        <button class="checkout-btn">Continue to checkout</button>
    </div>
</div>
</body>
</html>
