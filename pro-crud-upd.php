<?php
session_start();
include "nav.html";

if (!isset($_SESSION['admin'])) {
    die("Access Denied");
}

if (!isset($_GET['id'])) {
    die("Geen id meegegeven");
}

$id = $_GET['id'];

$username = "root";
$password = "";
$dbname = "the-croods";
$dsn = "mysql:host=localhost;dbname=$dbname;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT * FROM product WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        die("Product niet gevonden");
    }

} catch (PDOException $e) {
    die("Fout: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Product Bewerken</title>
    <link rel="stylesheet" href="company.css">
</head>
<body>

<h1>Product Bewerken</h1>

<a href="pro-crud-get.php">← Terug naar overzicht</a>

<form action="pro-crud-update.php" method="POST">
  <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">

    <label>Productnaam:</label><br>
    <input type="text" name="productname" value="<?= htmlspecialchars($product['productname']) ?>" required><br><br>

    <label>Ingredients:</label><br>
    <input type="text" name="ingredients" value="<?= htmlspecialchars($product['ingredients'] ?? '') ?>"><br><br>

    <label>Allergens:</label><br>
    <input type="text" name="allergens" value="<?= htmlspecialchars($product['allergens'] ?? '') ?>"><br><br>

    <label>Prijs:</label><br>
    <input type="text" name="price" value="<?= htmlspecialchars($product['price']) ?>" required><br><br>

    <label>Categorie ID:</label><br>
    <input type="number" name="categoryid" value="<?= htmlspecialchars($product['categoryid']) ?>" required><br><br>

    <label>Supplier ID:</label><br>
    <input type="number" name="supplierid" value="<?= htmlspecialchars($product['supplierid']) ?>" required><br><br>

    <label>Actief:</label><br>
    <input type="text" name="isactive" value="<?= htmlspecialchars($product['isactive']) ?>"><br><br>

    <button type="submit">Opslaan</button>
    <a href="pro-crud-get.php">Annuleren</a>
</form>

</body>
</html>