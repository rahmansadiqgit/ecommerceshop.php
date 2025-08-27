<?php
include 'db.php';
include 'header.php';

$search = $_GET['search'] ?? "";
$like = "%$search%";

$sql = "SELECT * FROM products WHERE name LIKE '$like'";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Shop</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Welcome to Grocery Shop</h1>

<form method="get">
    <input type="text" name="search" placeholder="Search..." value="<?= htmlspecialchars($search) ?>">
    <button>Search</button>
</form>

<div class="products">
<?php while($row = $result->fetch_assoc()): ?>
    <div class="product">
        <h3><?= htmlspecialchars($row['name']) ?></h3>
        <p>৳<?= $row['price'] ?> | Stock: <?= $row['quantity'] ?></p> 
        <p><?= $row['description'] ?> </p>
        <form method="POST" action="cart.php">
            <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
            <input type="number" name="qty" value="1" min="1" max="<?= $row['quantity'] ?>">
            <button type="submit" name="add">Add to Cart</button>
        </form>
    </div>
<?php endwhile; ?>
</div>
<a href="cart.php">View Cart</a>
</body>
</html>
