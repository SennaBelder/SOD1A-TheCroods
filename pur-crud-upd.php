<?php
session_start();

if (!isset($_SESSION["clientid"]))
{
    header("Refresh: 4, url=login.php");
    echo "<h2>Je moet ingelogd zijn om een bestelling te kunnen wijzigen!</h2>";
    exit();
}
$cli_clientid = $_SESSION["clientid"];

require_once "dbconnect.php";

$sQuery = "SELECT pur.id AS purchaseid, pur.purchasedate, pl.id AS purchaselineid,
                  pl.quantity, pl.price, prod.productname
           FROM purchase pur
           INNER JOIN purchaseline pl ON pl.purchaseid = pur.id
           INNER JOIN product prod ON prod.id = pl.productid
           WHERE pur.clientid = :cli_clientid AND pur.delivered = 0
           ORDER BY pur.id, pl.id";
$oStmt = $db->prepare($sQuery);
$oStmt->bindValue(":cli_clientid", $cli_clientid);
$oStmt->execute();
$aRows = $oStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Bestelling wijzigen</title>
</head>
<body>

<h2>Bestelling wijzigen</h2>

<?php if (count($aRows) === 0): ?>

    <p>Je hebt geen openstaande bestellingen om te wijzigen.</p>

<?php else: ?>

    <form action="pur-crud-upd01.php" method="POST">
    <table border="1">
        <tr>
            <th>Aankoop ID</th>
            <th>Datum</th>
            <th>Product</th>
            <th>Prijs</th>
            <th>Aantal</th>
        </tr>
        <?php foreach ($aRows as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row["purchaseid"]) ?></td>
            <td><?= htmlspecialchars($row["purchasedate"]) ?></td>
            <td><?= htmlspecialchars($row["productname"]) ?></td>
            <td>&euro; <?= number_format($row["price"], 2, ",", ".") ?></td>
            <td>
                <input type="number"
                       name="aantal[<?= (int)$row["purchaselineid"] ?>]"
                       value="<?= (int)$row["quantity"] ?>"
                       min="1"
                       required>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <br>
    <button type="submit">Opslaan</button>

    </form>

<?php endif; ?>

</body>
</html>