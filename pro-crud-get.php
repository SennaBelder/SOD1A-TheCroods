<?php
session_start();



   
   

$username = "root";
$password = "";
$dbname = "the-croods";
$dsn = "mysql:host=localhost;dbname=$dbname;charset=utf8mb4";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Onderhoud producten</title>
</head>
<body>

<h1>Onderhoud producten</h1>


<a href="pro-crud-add.php">
    <button>Product toevoegen</button>
</a>

<br><br>

<?php
try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT product.id, product.productname, product.price,
                   category.name AS category_name,
                   supplier.company AS supplier_name,
                   product.isactive
            FROM product
            JOIN category ON product.categoryid = category.id
            JOIN supplier ON product.supplierid = supplier.id";

    $stmt = $pdo->query($sql);

    echo "<table border='1' cellpadding='8'>";
    echo "<tr>
            <th>ID</th>
            <th>Naam</th>
            <th>Prijs</th>
            <th>Categorie</th>
            <th>Leverancier</th>
            <th>Actief</th>
            <th>Acties</th>
          </tr>";

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['productname'] . "</td>";
        echo "<td>€" . $row['price'] . "</td>";
        echo "<td>" . $row['category_name'] . "</td>";
        echo "<td>" . $row['supplier_name'] . "</td>";
        echo "<td>" . ($row['isactive'] == 'J' ? 'Ja' : 'Nee') . "</td>";

       
        echo "<td>
                <a href='pro-crud-upd.php?id=" . $row['id'] . "'>
                    <button>Wijzigen</button>
                </a>

                <a href='pro-crud-del.php?id=" . $row['id'] . "'>
                    <button>Verwijderen</button>
                </a>
              </td>";

        echo "</tr>";
    }

    echo "</table>";

} catch (PDOException $e) {
    echo "Fout: " . $e->getMessage();
}
?>

</body>
</html>