<?php
session_start();
include "nav.html";

if (!isset($_SESSION["benJeErAl"]) || $_SESSION["SoortToegang"] !== "Klant") {
    header("Refresh: 4, url=login.php");
    echo "<h2>Je moet ingelogd zijn als klant!</h2>";
    exit();
}

$cli_clientid = $_SESSION["welkNummerIsDit"];

if (!isset($_SESSION["wijzigingen"]) || !is_array($_SESSION["wijzigingen"])) {
    header("Refresh: 4, url=pur-crud-upd.php");
    echo "<h2>Geen wijzigingen om op te slaan!</h2>";
    exit();
}

require_once "dbconnect.php";

try {
    foreach ($_SESSION["wijzigingen"] as $row) {
        $sQuery = "UPDATE purchaseline pl
                   INNER JOIN purchase pur ON pur.id = pl.purchaseid
                   SET pl.quantity = :nieuw_aantal
                   WHERE pl.id = :purchaselineid AND pur.clientid = :cli_clientid AND pur.delivered = 0";
        $oStmt = $db->prepare($sQuery);
        $oStmt->bindValue(":nieuw_aantal", $row["nieuw_aantal"]);
        $oStmt->bindValue(":purchaselineid", $row["id"]);
        $oStmt->bindValue(":cli_clientid", $cli_clientid);
        $oStmt->execute();
    }
} catch (PDOException $e) {
    $sMsg = '<p>Regelnummer: ' . $e->getLine() . '<br />Bestand: ' . $e->getFile() . '<br />Foutmelding: ' . $e->getMessage() . '</p>';
    trigger_error($sMsg);
    die();
}

unset($_SESSION["wijzigingen"]);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="company.css">
    <title>Bestelling gewijzigd</title>
</head>
<body>

<h2>Bestelling is gewijzigd</h2>
<p><a class="link" href="pur-crud-upd.php">Terug naar je bestellingen</a></p>

</body>
</html>