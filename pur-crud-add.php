<?php
session_start();
include "nav.html";
require_once 'dbconnect.php';

$sql = "
    SELECT p.id, p.productname, c.name AS category_name, p.price
    FROM product p
    JOIN category c ON p.categoryid = c.id
    WHERE p.isactive = 'J'
    ORDER BY c.name, p.productname
";

if (!isset($_SESSION["benJeErAl"]) || $_SESSION["SoortToegang"] !== "Klant")
{
    header("Refresh: 4, url=login.php");
    echo "<h2>Je moet ingelogd zijn als klant om producten te kunnen bestellen!</h2>";
    exit();
}


$stmt = $db->query($sql);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Add product</title>
    <link rel="stylesheet" href="company.css">
</head>
<body>

<h2>LET OP: je kan maar één product tegelijk bestellen</h2>

<?php if (count($rows) > 0): ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Productnaam</th>
                <th>Categorie</th>
                <th>Prijs</th>
                <th>Aantal</th>
                <th>Actie</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['productname']) ?></td>
                <td><?= htmlspecialchars($row['category_name']) ?></td>
                <td>€ <?= number_format($row['price'], 2, ',', '.') ?></td>
                <td>
                    <form action="pur-crud-adding.php" method="POST">
                        <input type="hidden" name="prod_applyinsert" value="1">
                        <input type="hidden" name="productid" value="<?= (int)$row['id'] ?>">
                        <input type="hidden" name="prod_price" value="<?= htmlspecialchars($row['price']) ?>">
                        <input type="number" name="aantal" value="0" min="1" required>
                        <button type="submit">Bestellen</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php else: ?>
    <p>Er zijn momenteel geen actieve producten beschikbaar</p>
<?php endif; ?>

</body>
</html>