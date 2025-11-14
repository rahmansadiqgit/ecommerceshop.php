<?php
session_start();
include 'db.php';

// ===== Encryption function =====
function encryptTransposition($text, $key=[4,5,2]) {
    $columns = count($key);
    $rows = ceil(strlen($text)/$columns);

    $matrix = array_fill(0, $rows, array_fill(0, $columns, ''));
    $k = 0;
    for($r=0; $r<$rows; $r++){
        for($c=0; $c<$columns; $c++){
            if($k < strlen($text)) $matrix[$r][$c] = $text[$k++];
            else $matrix[$r][$c] = '_'; // padding character
        }
    }

    $sortedKey = $key;
    sort($sortedKey);
    $columnOrder = [];
    foreach($sortedKey as $val) $columnOrder[] = array_search($val, $key);

    $ciphertext = '';
    for($ci=0; $ci<$columns; $ci++){
        $c = $columnOrder[$ci];
        for($r=0; $r<$rows; $r++){
            $ciphertext .= $matrix[$r][$c];
        }
    }

    return $ciphertext;
}


// ===== Handle registration =====
if(isset($_POST['register'])){
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $address = $conn->real_escape_string($_POST['address']);

    $encryptedPassword = encryptTransposition($password);

    $conn->query("INSERT INTO users (name,email,password,address) 
                  VALUES ('$name','$email','$encryptedPassword','$address')");

    $user_id = $conn->insert_id;
    $conn->query("UPDATE users SET card_number=$user_id WHERE id=$user_id");

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
<h2>Register</h2>
<form method="POST">
    Name: <input type="text" name="name" required><br>
    Email: <input type="email" name="email" required><br>
    Password: <input type="password" name="password" required><br>
    Address: <textarea name="address"></textarea><br>
    <button type="submit" name="register">Register</button>
</form>
<a href="login.php"><button type="button">Log In</button></a>
<a href="index.php"><button type="button">Back to Shop</button></a>
</body>
</html>
