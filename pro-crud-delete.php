<?php
session_start();




   
   

$username = "root";
$password = "";
$dbname = "the-croods";
$dsn = "mysql:host=localhost;dbname=$dbname;charset=utf8mb4";


$pdo = new PDO("mysql:host=localhost;dbname=the-croods;charset=utf8mb4", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$stmt = $pdo->prepare("DELETE FROM product WHERE id = :id");
$stmt->execute(['id' => $id]);

header("Location: pro-crud-get.php?success=deleted");
exit;
?>