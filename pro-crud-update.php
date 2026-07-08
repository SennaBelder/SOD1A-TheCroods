<?php
session_start();

if (!isset($_SESSION['admin'])) {
    die("Access Denied");
}

$id = $_POST['id'] ?? '';
$productname = $_POST['productname'] ?? '';
$ingredients = $_POST['ingredients'] ?? '';
$allergens = $_POST['allergens'] ?? '';
$price = $_POST['price'] ?? '';
$categoryid = $_POST['categoryid'] ?? '';
$supplierid = $_POST['supplierid'] ?? '';

if (empty($id)) {
    die("Geen product ID meegegeven");
}

if (empty($productname) || !preg_match("/^[a-zA-Z0-9À-ÿ ]+$/", $productname)) {
    die("Productnaam ongeldig");
}

if (!empty($ingredients) && !preg_match("/^[a-zA-Z0-9À-ÿ ,.-]*$/", $ingredients)) {
    die("Ingredients ongeldig");
}

if (!empty($allergens) && !preg_match("/^[a-zA-Z0-9À-ÿ ,.-]*$/", $allergens)) {
    die("Allergens ongeldig");
}

$price = str_replace(',', '.', $price);

if (empty($price) || !is_numeric($price)) {
    die("Prijs moet een geldig getal zijn, bijvoorbeeld 12,34 of 12.34");
}

if (empty($categoryid) || empty($supplierid)) {
    die("Categorie en supplier zijn verplicht");
}

$username = "root";
$password = "";
$dbname = "the-croods";
$dsn = "mysql:host=localhost;dbname=$dbname;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("
        UPDATE product 
        SET productname = :productname,
            ingredients = :ingredients,
            allergens = :allergens,
            price = :price,
            categoryid = :categoryid,
            supplierid = :supplierid
        WHERE id = :id
    ");

    $stmt->execute([
        ':id' => $id,
        ':productname' => $productname,
        ':ingredients' => $ingredients,
        ':allergens' => $allergens,
        ':price' => $price,
        ':categoryid' => $categoryid,
        ':supplierid' => $supplierid
    ]);

    header("Location: pro-crud-get.php?success=updated");
    exit;

} catch (PDOException $e) {
    die("Fout: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
      
</head>
<body>
    
</body>
</html>