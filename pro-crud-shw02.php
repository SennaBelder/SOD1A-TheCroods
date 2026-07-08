<?php
session_start();
include "nav.html";

if (!isset($_SESSION['admin'])) {
   die("Access Denied");
} else {
    echo "Welkom, admin!";
}

$username = "root";
$password = "";
$dbname = "the-croods";
$dsn = "mysql:host=localhost;dbname=$dbname;charset=utf8mb4";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Alle producten</title>
     <link rel="stylesheet" href="company.css">
</head>
<body>

<h2>Alle producten</h2>

<?php
try {
    
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    
    $sql = "SELECT product.id, product.productname, 
                   category.name AS category_name,
                   supplier.company AS supplier_name,
                   product.price, product.isactive
            FROM product
            JOIN category ON product.categoryid = category.id
            JOIN supplier ON product.supplierid = supplier.id
            WHERE product.isactive = 'J' OR product.isactive = 'N'";

    $stmt = $pdo->query($sql);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<p>";
        echo "ID: " . $row['id'] . "<br>";
        echo "Naam: " . $row['productname'] . "<br>";
        echo "Categorie: " . $row['category_name'] . "<br>";
        echo "Leverancier: " . $row['supplier_name'] . "<br>";
        echo "Prijs: €" . $row['price'] . "<br>";
        echo "Actief: " . ($row['isactive'] == 'J' ? 'Ja' : 'Nee') . "<br>";
        echo "</p><hr>";
    }

} catch (PDOException $e) {
    echo "Fout: " . $e->getMessage();
}
?>

</body>
</html>