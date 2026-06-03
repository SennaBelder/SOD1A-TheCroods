<?php



$id = $_POST['id'] ?? '';
$productname = $_POST['productname'] ?? '';
$ingredients = $_POST['ingredients'] ?? '';
$allergens = $_POST['allergens'] ?? '';
$price = $_POST['price'] ?? '';
$categoryid = $_POST['categoryid'] ?? '';
$supplierid = $_POST['supplierid'] ?? '';



if (empty($productname) || !preg_match("/^[a-zA-Z ]+$/", $productname)) {
    die("Productnaam ongeldig");
}


if (!empty($ingredients) && !preg_match("/^[a-zA-Z0-9 ]*$/", $ingredients)) {
    die("Ingredients ongeldig");
}


if (!empty($allergens) && !preg_match("/^[a-zA-Z0-9 ]*$/", $allergens)) {
    die("Allergens ongeldig");
}


if (empty($price) || !preg_match("/^[0-9]+,[0-9]{2}$/", $price)) {
    die("Prijs moet formaat 12,34 zijn");
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
        'id' => $id,
        'productname' => $productname,
        'ingredients' => $ingredients,
        'allergens' => $allergens,
        'price' => $price,
        'categoryid' => $categoryid,
        'supplierid' => $supplierid
    ]);

   

   
    header("Location: pro-crud-get.php?success=updated");
    exit;

} catch (PDOException $e) {
    die("Fout: " . $e->getMessage());
}