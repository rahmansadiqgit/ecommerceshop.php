<?php
include 'db.php';
session_start();

// Check if user is logged in
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch current user info
$result = $conn->query("SELECT * FROM users WHERE id=$user_id");
$user = $result->fetch_assoc();

// Handle profile update
if(isset($_POST['update'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    $sql = "UPDATE users 
    SET name='$name', email='$email', address='$address' 
    WHERE id=$user_id";

$conn->query($sql);


    echo "<p style='color:green;'>Profile updated successfully!</p>";
    $user = ['name'=>$name, 'email'=>$email, 'address'=>$address, 'card_number'=>$user['card_number']]; // keep local copy
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Profile</title>
</head>
<body>
<h2>Your Profile</h2>
<form method="POST">
    Name: <input type="text" name="name" value="<?= $user['name'] ?>" required><br><br>
    Email: <input type="email" name="email" value="<?= $user['email'] ?>" required><br><br>
    Address: <textarea name="address"><?= $user['address'] ?></textarea><br><br>
</form>
<button type="submit" name="update">Update Profile</button>

<p>Card Number: <?= $user['card_number'] ?></p> <!-- Display card number but not editable -->

<a href="index.php">Back to Shop</a> | <a href="logout.php">Logout</a>
</body>
</html>
