<?php

$username = 'root';
$password = '';
$dbname = 'the-croods';
$dsn = "mysql:host=localhost;dbname=$dbname;charset=utf8mb4";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Producten</title>
</head>
<body>

<h2>Actieve producten</h2>

<?php


try {
     $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT product.productname, product.allergens, category.name AS category_name, product.price
            FROM product
            JOIN category ON product.categoryid = category.id
            WHERE product.isactive = 'J'";

    $stmt = $pdo->query($sql);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<p>";
        echo "Naam: " . $row['productname'] . "<br>";
        echo "Allergenen: " . $row['allergens'] . "<br>";
        echo "Categorie: " . $row['category_name'] . "<br>";
        echo "Prijs: €" . $row['price'] . "<br>";
        echo "</p><hr>";
    }

} catch (PDOException $e) {
    echo "Fout: " . $e->getMessage();
}
?>

</body>
</html>