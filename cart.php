<?php
session_start();
include 'db.php';

// Initialize cart if not set
if(!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

// Add product to cart
if(isset($_POST['add'])){
    $id = (int)$_POST['product_id']; // cast to int for safety
    $qty = max(1, (int)$_POST['qty']);
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + $qty;
}

// Remove product from cart
if(isset($_POST['remove'])){
    $id = (int)$_POST['product_id'];
    if(isset($_SESSION['cart'][$id])){
        unset($_SESSION['cart'][$id]);
    }
}

$cart = $_SESSION['cart'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cart</title>
</head>
<body>
<h2>Your Cart</h2>

<?php if(!$cart): ?>
    <p>Cart is empty.</p>
    <a href="index.php">Continue Shopping</a>
    <?php exit; ?>
<?php endif; ?>

<form method="POST" action="checkout.php">
<table border="1" cellpadding="5" cellspacing="0">
<tr>
    <th>Product</th>
    <th>Qty</th>
    <th>Subtotal</th>
    <th>Action</th>
</tr>
<?php
$total = 0;
foreach($cart as $id => $qty):
    $id = (int)$id; // extra safety
    $result = $conn->query("SELECT * FROM products WHERE id=$id");
    $p = $result->fetch_assoc();
    $sub = $p['price'] * $qty;
    $total += $sub;
?>
<tr>
    <td><?= htmlspecialchars($p['name']) ?></td>
    <td><?= $qty ?></td>
    <td>৳<?= $sub ?></td>
    <td>
        <!-- Remove button -->
        <form method="POST" style="display:inline;">
            <input type="hidden" name="product_id" value="<?= $id ?>">
            <button type="submit" name="remove">Remove</button>
        </form>
    </td>
</tr>
<?php endforeach; ?>
<tr>
    <td colspan="2"><strong>Total</strong></td>
    <td colspan="2">৳<?= $total ?></td>
</tr>
</table>

<br>
<button type="submit">Checkout</button>
</form>

<br>
<a href="index.php">Continue Shopping</a>
</body>
</html>
