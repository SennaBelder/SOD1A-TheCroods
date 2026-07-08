<?php
session_start();
include "nav.html";

if (!isset($_SESSION["SoortToegang"]) || $_SESSION["SoortToegang"] !== "Beheer")
{
    header("Refresh: 4, url=login.php");
    echo "<h2>Je moet ingelogd zijn als beheerder om bestellingen te kunnen verwijderen!</h2>";
    exit();
}

require_once "dbconnect.php";

$sQuery = "SELECT pur.id AS purchaseid, cli.last_name, pur.purchasedate,
                  pl.id AS purchaselineid, prod.productname, pl.quantity
           FROM purchase pur
           INNER JOIN client cli ON cli.id = pur.clientid
           INNER JOIN purchaseline pl ON pl.purchaseid = pur.id
           INNER JOIN product prod ON prod.id = pl.productid
           WHERE pur.delivered = 0
           ORDER BY pur.id, pl.id";
$oStmt = $db->prepare($sQuery);
$oStmt->execute();
$aRows = $oStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Bestellingen verwijderen</title>
    <link rel="stylesheet" href="company.css">
</head>
<body>

<h2>Openstaande bestellingen</h2>

<?php if (count($aRows) === 0): ?>

    <p>Er zijn geen openstaande bestellingen.</p>

<?php else: ?>

    <table>
        <tr>
            <th>Aankoop ID</th>
            <th>Achternaam klant</th>
            <th>Datum</th>
            <th>Regel ID</th>
            <th>Product</th>
            <th>Aantal</th>
            <th>Actie</th>
        </tr>
        <?php foreach ($aRows as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row["purchaseid"]) ?></td>
            <td><?= htmlspecialchars($row["last_name"]) ?></td>
            <td><?= htmlspecialchars($row["purchasedate"]) ?></td>
            <td><?= htmlspecialchars($row["purchaselineid"]) ?></td>
            <td><?= htmlspecialchars($row["productname"]) ?></td>
            <td><?= htmlspecialchars($row["quantity"]) ?></td>
            <td>
                <form action="pur-crud-delete.php" method="POST" style="display:inline">
                    <input type="hidden" name="purchaselineid" value="<?= (int)$row["purchaselineid"] ?>">
                    <input type="hidden" name="purchaseid" value="<?= (int)$row["purchaseid"] ?>">
                    <button type="submit" name="actie" value="regel">Regel</button>
                </form>
                <form action="pur-crud-delete.php" method="POST" style="display:inline">
                    <input type="hidden" name="purchaseid" value="<?= (int)$row["purchaseid"] ?>">
                    <button type="submit" name="actie" value="aankoop">Aankoop</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

<?php endif; ?>

</body>
</html>