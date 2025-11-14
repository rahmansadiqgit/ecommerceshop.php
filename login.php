<?php
session_start();
include 'db.php';

// ===== Decryption function =====
function decryptTransposition($ciphertext, $key=[4,5,2]){
    $columns = count($key);
    $rows = ceil(strlen($ciphertext)/$columns);

    $sortedKey = $key;
    sort($sortedKey);
    $columnOrder = [];
    foreach($sortedKey as $val) $columnOrder[] = array_search($val, $key);

    $colLengths = array_fill(0, $columns, $rows);

    $matrix = array_fill(0, $rows, array_fill(0, $columns, ''));
    $k = 0;
    for($ci=0; $ci<$columns; $ci++){
        $c = $columnOrder[$ci];
        for($r=0; $r<$colLengths[$c]; $r++){
            $matrix[$r][$c] = $ciphertext[$k++];
        }
    }

    $plaintext = '';
    for($r=0; $r<$rows; $r++){
        for($c=0; $c<$columns; $c++){
            if($matrix[$r][$c] !== '_') $plaintext .= $matrix[$r][$c]; // remove padding
        }
    }

    return $plaintext;
}


// ===== Handle login =====
if(isset($_POST['login'])){
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE email='$email'");
    $user = $result->fetch_assoc();

    if($user){
        $decryptedPassword = decryptTransposition($user['password']);
        echo "Decrypted password: " . $decryptedPassword; 
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
<h2>Login</h2>
<form method="POST">
    Email: <input type="email" name="email" required><br>
    Password: <input type="password" name="password" required><br>
    <button type="submit" name="login">Login</button>
</form>
<a href="register.php"><button type="button">Registration</button></a>
<a href="index.php"><button type="button">Back to Shop</button></a>
</body>
</html>
