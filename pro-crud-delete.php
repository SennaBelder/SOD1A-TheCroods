<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Ongeldige toegang");
}

$id = $_POST['id'] ?? '';

if (empty($id)) {
    die("Geen id ontvangen");
}


$pdo = new PDO("mysql:host=localhost;dbname=the-croods;charset=utf8mb4", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$stmt = $pdo->prepare("DELETE FROM product WHERE id = :id");
$stmt->execute(['id' => $id]);


header("Location: pro-crud-get.php?success=deleted");
exit;