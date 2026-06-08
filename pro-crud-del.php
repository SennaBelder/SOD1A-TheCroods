<?php




if (!isset($_GET['id'])) {
    die("Geen id meegegeven");
}

$id = $stmt['id'];

 
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
    orderregels
    JOIN orders o ON ol.orderid = o.id
    WHERE ol.productid = :id 
    AND o.delivered = 0
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