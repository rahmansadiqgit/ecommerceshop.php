<?php
session_start();
include 'db.php';

// Simple Columnar Transposition Cipher
function encryptTransposition($text, $key = 8) {
    $text = str_replace(' ', '', $text); // remove spaces
    $columns = $key;
    $rows = ceil(strlen($text) / $columns);
    $matrix = array_fill(0, $rows, array_fill(0, $columns, ''));
    
    // Fill matrix row-wise
    $k = 0;
    for ($r = 0; $r < $rows; $r++) {
        for ($c = 0; $c < $columns; $c++) {
            if ($k < strlen($text)) {
                $matrix[$r][$c] = $text[$k++];
            }
        }
    }

    // Read column-wise to get encrypted text
    $encrypted = '';
    for ($c = 0; $c < $columns; $c++) {
        for ($r = 0; $r < $rows; $r++) {
            $encrypted .= $matrix[$r][$c];
        }
    }

    return $encrypted;
}

if(isset($_POST['register'])){
    $name     = $conn->real_escape_string($_POST['name']);
    $email    = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $address  = $conn->real_escape_string($_POST['address']);

    // Encrypt the password
    $encryptedPassword = encryptTransposition($password);

    // Insert user with encrypted password
    $conn->query("INSERT INTO users (name, email, password, address) 
                  VALUES ('$name', '$email', '$encryptedPassword', '$address')");

    // Assign card_number as user_id
    $user_id = $conn->insert_id;
    $conn->query("UPDATE users SET card_number = $user_id WHERE id = $user_id");

    header("Location: login.php");
    exit;
}
?>
