<?php
session_start();
include 'db.php';
if(!isset($_SESSION['user_id'])) header("Location: login.php");

$cart = $_SESSION['cart'] ?? [];
$total = 0;

foreach($cart as $id => $qty){
    $row = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();
    $total += $row['price'] * $qty;
}

$user_id = (int)$_SESSION['user_id'];
$total   = (float)$total;

$sql = "INSERT INTO orders (user_id, total_amount, order_date) 
        VALUES ($user_id, $total, NOW())";

$conn->query($sql);

$order_id = $conn->insert_id; // get the last inserted ID


if (isset($_SESSION['user_id'])) {
foreach($cart as $id => $qty){
    $order_id = (int)$order_id;
    $id       = (int)$id;
    $qty      = (int)$qty;
    
    // Always insert order items
    $conn->query("INSERT INTO order_items (order_id, product_id, quantity) 
                  VALUES ($order_id, $id, $qty)");
    
    // Reduce stock only if user is logged in
    
        $conn->query("UPDATE products 
                      SET quantity = quantity - $qty 
                      WHERE id = $id");
    }
    
}

$_SESSION['cart'] = [];
echo "Order placed successfully! <a href='index.php'>Back to Shop</a>";
