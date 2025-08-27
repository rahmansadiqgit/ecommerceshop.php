<?php
include 'db.php';

if(isset($_POST['register'])){
    // Escape strings for safety
    $name     = $conn->real_escape_string($_POST['name']);
    $email    = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $address  = $conn->real_escape_string($_POST['address']);

    // Insert user without card_number
    $conn->query("INSERT INTO users (name, email, password, address) 
                  VALUES ('$name', '$email', '$password', '$address')");

    // Assign card_number as user_id
    $user_id = $conn->insert_id;
    $conn->query("UPDATE users SET card_number = $user_id WHERE id = $user_id");

    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
<a href="login.php">
    <button type="button">Log In</button>
</a>
<a href="index.php"><button type="button">Back to Shop</button></a> 

<h2>Register</h2>
<form method="POST">
    Name: <input type="text" name="name" required><br>
    Email: <input type="email" name="email" required><br>
    Password: <input type="password" name="password" required><br>
    Address: <textarea name="address"></textarea><br>
    <button type="submit" name="register">Register</button>
</form>
</body>
</html>
