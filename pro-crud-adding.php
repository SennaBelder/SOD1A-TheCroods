<?php
session_start();
include "nav.html";

if (!isset($_SESSION['admin'])) {
    die("Access Denied");
}

$username = "root";
$password = "";
$dbname = "the-croods";
$dsn = "mysql:host=localhost;dbname=$dbname;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Fout: " . $e->getMessage());
}

 
$productname = $_POST['productname'] ?? '';
$ingredients = $_POST['ingredients'] ?? '';
$allergens = $_POST['allergens'] ?? '';
$price = $_POST['price'] ?? '';
$categoryid = $_POST['categoryid'] ?? '';
$supplierid = $_POST['supplierid'] ?? '';


$errors = [];


if (empty($productname) || !preg_match("/^[A-Za-z ]+$/", $productname)) {
    $errors[] = "Productnaam is ongeldig";
}


if (!empty($ingredients) && !preg_match("/^[A-Za-z0-9 ]*$/", $ingredients)) {
    $errors[] = "Ingredients ongeldig";
}


if (!empty($allergens) && !preg_match("/^[A-Za-z0-9 ]*$/", $allergens)) {
    $errors[] = "Allergens ongeldig";
}


if (empty($price) || !preg_match("/^[0-9]+,[0-9]{2}$/", $price)) {
    $errors[] = "Prijs moet formaat 12,34 hebben";
}


if (empty($categoryid)) {
    $errors[] = "Categorie verplicht";
}

if (empty($supplierid)) {
    $errors[] = "Leverancier verplicht";
}


if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<p style='color:red;'>$error</p>";
    }
    echo "<a href='pro-crud-add.php'>⬅ Terug</a>";
    exit;
}


$price = str_replace(',', '.', $price);


$sql = "INSERT INTO product 
(productname, ingredients, allergens, price, categoryid, supplierid, isactive)
VALUES (:productname, :ingredients, :allergens, :price, :categoryid, :supplierid, 'J')";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':productname' => $productname,
    ':ingredients' => $ingredients,
    ':allergens' => $allergens,
    ':price' => $price,
    ':categoryid' => $categoryid,
    ':supplierid' => $supplierid
]);


header("Location: pro-crud-get.php");
exit;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
     <link rel="stylesheet" href="company.css">
</head>
<body>
    
</body>
</html>