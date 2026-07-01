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

$sActie = $_POST["actie"] ?? '';


// Hele aankoop verwijderen (ook gebruikt als bevestiging na waarschuwing)

if ($sActie === "aankoop")
{
    $purchaseid = $_POST["purchaseid"] ?? '';

    if (empty($purchaseid) || !is_numeric($purchaseid))
    {
        header("Refresh: 4, url=pur-crud-del.php");
        echo "<h2>Geen geldige aankoop gekozen!</h2>";
        exit();
    }

    try
    {
        $sQuery = "DELETE FROM purchaseline WHERE purchaseid = :purchaseid";
        $oStmt = $db->prepare($sQuery);
        $oStmt->bindValue(":purchaseid", $purchaseid);
        $oStmt->execute();

        $sQuery = "DELETE FROM purchase WHERE id = :purchaseid";
        $oStmt = $db->prepare($sQuery);
        $oStmt->bindValue(":purchaseid", $purchaseid);
        $oStmt->execute();
    }
    catch (PDOException $e)
    {
        $sMsg = '<p>
                    Regelnummer: ' . $e->getLine() . '<br />
                    Bestand: ' . $e->getFile() . '<br />
                    Foutmelding: ' . $e->getMessage() . '
                </p>';
        trigger_error($sMsg);
        die();
    }
    ?>
    <!DOCTYPE html>
    <html lang="nl">
    <head>
        <meta charset="UTF-8">
        <title>Aankoop verwijderd</title>
    </head>
    <body>
        <h2>De volledige aankoop is verwijderd</h2>
        <p><a href="pur-crud-del.php">Terug naar het overzicht</a></p>
    </body>
    </html>
    <?php
    exit();
}


// Eén regel (purchaseline) verwijderen

if ($sActie === "regel")
{
    $purchaselineid = $_POST["purchaselineid"] ?? '';
    $purchaseid = $_POST["purchaseid"] ?? '';

    if (empty($purchaselineid) || !is_numeric($purchaselineid) || empty($purchaseid) || !is_numeric($purchaseid))
    {
        header("Refresh: 4, url=pur-crud-del.php");
        echo "<h2>Geen geldige bestelregel gekozen!</h2>";
        exit();
    }

    // Tellen hoeveel regels deze aankoop nog heeft
    $sQuery = "SELECT COUNT(*) AS aantal_regels FROM purchaseline WHERE purchaseid = :purchaseid";
    $oStmt = $db->prepare($sQuery);
    $oStmt->bindValue(":purchaseid", $purchaseid);
    $oStmt->execute();
    $row = $oStmt->fetch(PDO::FETCH_ASSOC);

    if ($row["aantal_regels"] <= 1)
    {
        // Dit is de laatste regel van deze aankoop -> waarschuwing tonen
        ?>
        <!DOCTYPE html>
        <html lang="nl">
        <head>
            <meta charset="UTF-8">
            <title>Waarschuwing</title>
        </head>
        <body>
            <h2>Laatste product bij deze aankoop</h2>
            <p>Wilt u het verwijderen afbreken of wilt u de hele aankoop verwijderen?</p>

            <form action="pur-crud-del.php" method="GET" style="display:inline">
                <button type="submit">Afbreken</button>
            </form>

            <form action="pur-crud-delete.php" method="POST" style="display:inline">
                <input type="hidden" name="purchaseid" value="<?= (int)$purchaseid ?>">
                <button type="submit" name="actie" value="aankoop">Verwijder aankoop</button>
            </form>
        </body>
        </html>
        <?php
        exit();
    }

    // Niet de laatste regel -> gewoon verwijderen
    try
    {
        $sQuery = "DELETE FROM purchaseline WHERE id = :purchaselineid AND purchaseid = :purchaseid";
        $oStmt = $db->prepare($sQuery);
        $oStmt->bindValue(":purchaselineid", $purchaselineid);
        $oStmt->bindValue(":purchaseid", $purchaseid);
        $oStmt->execute();
    }
    catch (PDOException $e)
    {
        $sMsg = '<p>
                    Regelnummer: ' . $e->getLine() . '<br />
                    Bestand: ' . $e->getFile() . '<br />
                    Foutmelding: ' . $e->getMessage() . '
                </p>';
        trigger_error($sMsg);
        die();
    }
    ?>
    <!DOCTYPE html>
    <html lang="nl">
    <head>
        <meta charset="UTF-8">
        <title>Regel verwijderd</title>
    </head>
    <body>
        <h2>De bestelregel is verwijderd</h2>
        <p><a href="pur-crud-del.php">Terug naar het overzicht</a></p>
    </body>
    </html>
    <?php
    exit();
}


// Geen geldige actie meegegeven
header("Refresh: 4, url=pur-crud-del.php");
echo "<h2>Ongeldige actie!</h2>";
exit();