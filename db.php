<?php
$host = "localhost";
$user = "root";
$pass = "root";
$db = "ecommerce";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("DB Connection failed: " . $conn->connect_error);
?>

