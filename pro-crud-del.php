<?php
session_start();



if (!isset($_GET['id'])) {
    die("Geen id meegegeven");
}

$id = $_GET['id'];

$username = "root";
$password = "";
$dbname = "the-croods";
$dsn = "mysql:host=localhost;dbname=$dbname;charset=utf8mb4";
 
$pdo = new PDO("mysql:host=localhost;dbname=the-croods;charset=utf8mb4", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$stmt = $pdo->prepare("SELECT * FROM product WHERE id = :id");
$stmt->execute(['id' => $id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("Product niet gevonden");
}


$check = $pdo->prepare("
    SELECT COUNT(*)
    FROM purchaseline pl
    JOIN purchase p ON pl.purchaseid = p.id
    WHERE pl.productid = :id
");

$check->execute(['id' => $id]); {
$count = $check->fetchColumn();
}
if ($count > 0) {
    header("Location: pro-crud-get.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Product verwijderen</title>
</head>
<body>

<h1>Product verwijderen</h1>

<p>Weet je zeker dat je dit product wilt verwijderen?</p>

<ul>
    <li>ID: <?= $product['id'] ?></li>
    <li>Naam: <?= $product['productname'] ?></li>
    <li>Prijs: <?= $product['price'] ?></li>
</ul>

<form action="pro-crud-delete.php" method="post">
    <input type="hidden" name="id" value="<?= $product['id'] ?>">

    <button type="submit">Verwijder</button>
    <a href="pro-crud-get.php">Breek af</a>
</form>

</body>
</html>