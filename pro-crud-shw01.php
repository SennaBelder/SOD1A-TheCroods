<?php
SESSION_START();
$_SESSION['admin'] = true;

IF(!isset($_SESSION['admin'])){
   die("Access Denied");
} else {
    echo "Welkom, admin!";
}




$username = "root";
$password = "";
$servername = "localhost";
$dbname = "the-croods";
$dsn = "mysql:host=localhost;dbname=$dbname;charset=utf8mb4";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>inactive producten</title>
</head>
<body>
    <h2>Inactive Producten</h2>
    <?php

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT product.id, product.productname, 
                   category.name AS category_name, 
                   supplier.company AS supplier_name, 
                   product.price
            FROM product
            JOIN category ON product.categoryid = category.id
            JOIN supplier ON product.supplierid = supplier.id
            WHERE product.isactive = 'N'";

    $stmt = $pdo->query($sql);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<p>";
        echo "ID: " . $row['id'] . "<br>";
        echo "Naam: " . $row['productname'] . "<br>";
        echo "Categorie: " . $row['category_name'] . "<br>";
        echo "Leverancier: " . $row['supplier_name'] . "<br>";
        echo "Prijs: €" . $row['price'] . "<br>";
        echo "</p><hr>";
    }

} catch (PDOException $e) {
    echo "Fout: " . $e->getMessage();
}
?>

    
    
</body>
</html>
