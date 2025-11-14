<?php
include 'db.php';
session_start();

// Decryption function for Columnar Transposition Cipher
function decryptTransposition($encryptedText, $key = 8) {
    $length = strlen($encryptedText);
    $rows = ceil($length / $key);
    $cols = $key;

    // Fill matrix column-wise
    $matrix = array_fill(0, $rows, array_fill(0, $cols, ''));
    $k = 0;
    for ($c = 0; $c < $cols; $c++) {
        for ($r = 0; $r < $rows; $r++) {
            if ($k < $length) {
                $matrix[$r][$c] = $encryptedText[$k++];
            }
        }
    }

    // Read row-wise to get decrypted text
    $decrypted = '';
    for ($r = 0; $r < $rows; $r++) {
        for ($c = 0; $c < $cols; $c++) {
            $decrypted .= $matrix[$r][$c];
        }
    }

    return $decrypted;
}

if(isset($_POST['login'])){
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // Fetch user from database
    $result = $conn->query("SELECT * FROM users WHERE email='$email'");
    $user   = $result->fetch_assoc();

    if($user){
        // Decrypt stored password
        $decryptedPassword = decryptTransposition($user['password']);

        if($password === $decryptedPassword){
            $_SESSION['user_id'] = $user['id'];
            header("Location: index.php");
            exit;
        } else {
            echo "Invalid credentials!";
        }
    } else {
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
