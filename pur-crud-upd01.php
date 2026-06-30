<?php
session_start();

if (!isset($_SESSION["clientid"]))
{
    header("Refresh: 4, url=login.php");
    echo "<h2>Je moet ingelogd zijn om een bestelling te kunnen wijzigen!</h2>";
    exit();
}
$cli_clientid = $_SESSION["clientid"];

if (!isset($_POST["aantal"]) || !is_array($_POST["aantal"]))
{
    header("Refresh: 4, url=pur-crud-upd.php");
    echo "<h2>Er zijn geen wijzigingen ontvangen!</h2>";
    exit();
}

require_once "dbconnect.php";

$aWijzigingen = [];

foreach ($_POST["aantal"] as $purchaselineid => $aantal)
{
    $purchaselineid = (int)$purchaselineid;
    $aantal = trim($aantal);

    if (!is_numeric($aantal) || $aantal < 1)
    {
        header("Refresh: 4, url=pur-crud-upd.php");
        echo "<h2>Het aantal moet minimaal 1 zijn!</h2>";
        exit();
    }

    // Check dat deze purchaseline echt bij deze klant hoort
    $sQuery = "SELECT pl.id, pl.quantity, pl.price, prod.productname, pur.id AS purchaseid, pur.purchasedate
               FROM purchaseline pl
               INNER JOIN purchase pur ON pur.id = pl.purchaseid
               INNER JOIN product prod ON prod.id = pl.productid
               WHERE pl.id = :purchaselineid AND pur.clientid = :cli_clientid AND pur.delivered = 0";
    $oStmt = $db->prepare($sQuery);
    $oStmt->bindValue(":purchaselineid", $purchaselineid);
    $oStmt->bindValue(":cli_clientid", $cli_clientid);
    $oStmt->execute();
    $row = $oStmt->fetch(PDO::FETCH_ASSOC);

    if ($row === false)
    {
        header("Refresh: 4, url=pur-crud-upd.php");
        echo "<h2>Ongeldige bestelregel!</h2>";
        exit();
    }

    $row["nieuw_aantal"] = (int)$aantal;
    $aWijzigingen[] = $row;
}

$_SESSION["wijzigingen"] = $aWijzigingen;
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Wijziging bevestigen</title>
</head>
<body>

<h2>Controleer je wijziging</h2>

<form action="pur-crud-update.php" method="POST">
<table border="1">
    <tr>
        <th>Aankoop ID</th>
        <th>Datum</th>
        <th>Product</th>
        <th>Oud aantal</th>
        <th>Nieuw aantal</th>
    </tr>
    <?php foreach ($aWijzigingen as $row): ?>
    <tr>
        <td><?= htmlspecialchars($row["purchaseid"]) ?></td>
        <td><?= htmlspecialchars($row["purchasedate"]) ?></td>
        <td><?= htmlspecialchars($row["productname"]) ?></td>
        <td><?= htmlspecialchars($row["quantity"]) ?></td>
        <td><?= htmlspecialchars($row["nieuw_aantal"]) ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<br>
<button type="submit">Bevestigen</button>
</form>

<p><a href="pur-crud-upd.php">Terug zonder op te slaan</a></p>

</body>
</html>