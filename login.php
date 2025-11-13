<?php
include 'db.php';
session_start();

if(isset($_POST['login'])){
    $email = $conn->real_escape_string($_POST['email']); // escape for safety
    $password = $_POST['password'];

    // Run simple query
    $result = $conn->query("SELECT * FROM users WHERE email='$email'");
    $user   = $result->fetch_assoc();

    if($user && $password === $user['password']){
        $_SESSION['user_id'] = $user['id'];
        header("Location: index.php");
        exit;
    }
     else {
        echo "Invalid credentials!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
<a href="register.php">
    <button type="button">Registration</button>
</a>
<a href="index.php"><button type="button">Back to Shop</button></a> 

<h2>Login</h2>
<form method="POST">
    Email: <input type="email" name="email" required><br>
    Password: <input type="password" name="password" required><br>
    <button type="submit" name="login">Login</button>
</form>
</body>
</html>
