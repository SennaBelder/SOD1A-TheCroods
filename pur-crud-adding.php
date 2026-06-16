<?php
    session_start();

    // // Controleer of de gebruiker afkomt van het bestelformulier
    // // Dat weet je doordat hij daar op de knop "Bestellen" heeft gedrukt
    // // (vereist een hidden input <input type="hidden" name="prod_applyinsert" value="1"> in pur-crud-add.php)
    // if (!isset($_POST["prod_applyinsert"]))
    // {
    //     header("Refresh: 4, url=pur-crud-add.php");
    //     echo "<h2>Je bent hier niet op de juiste manier gekomen!</h2>";
    //     exit();
    // }

    // Controleer of er een ingelogde klant is
    if (!isset($_SESSION["clientid"]))
    {
        header("Refresh: 4, url=login.php");
        echo "<h2>Je moet ingelogd zijn om een bestelling te kunnen plaatsen!</h2>";
        exit();
    }
    $cli_clientid = $_SESSION["clientid"];

    // Haal de ingevulde gegevens uit het formulier
    $prod_productid = test_input($_POST["prod_productid"] ?? '');
    $prod_price     = test_input($_POST["prod_price"]     ?? '');
    $pur_quantity   = test_input($_POST["pur_quantity"]   ?? '');

    // Controleer of er een geldig product is gekozen
    if (empty($prod_productid) || !is_numeric($prod_productid))
    {
        header("Refresh: 4, url=pur-crud-add.php");
        echo "<h2>Er is geen geldig product gekozen!</h2>";
        exit();
    }

    // Controleer of de prijs geldig is
    if ($prod_price === '' || !is_numeric($prod_price))
    {
        header("Refresh: 4, url=pur-crud-add.php");
        echo "<h2>De prijs van het product ontbreekt of is ongeldig!</h2>";
        exit();
    }

    // Controleer of het aantal geldig is (minimaal 1)
    if (empty($pur_quantity) || !is_numeric($pur_quantity) || $pur_quantity < 1)
    {
        header("Refresh: 4, url=pur-crud-add.php");
        echo "<h2>Het aantal moet minimaal 1 zijn!</h2>";
        exit();
    }

    require_once "dbconnect.php";

    // STAP 1: Purchase-record aanmaken, maar alleen als er nog geen bestelling loopt
    if (!isset($_SESSION["purchaseid"]))
    {
        try
        {
            $sQuery = "INSERT INTO `purchase` (`clientid`, `purchasedate`, `delivered`)
                                VALUES (:cli_clientid, :pur_purchasedate, :pur_delivered)";
            $oStmt = $db->prepare($sQuery);
            $oStmt->bindValue(":cli_clientid", $cli_clientid);
            $oStmt->bindValue(":pur_purchasedate", date("Y-m-d"));
            $oStmt->bindValue(":pur_delivered", 0);
            $oStmt->execute();

            // Nieuwe purchase ID ophalen en bewaren in SESSION
            $_SESSION["purchaseid"] = $db->lastInsertId();
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
    }

    $pur_purchaseid = $_SESSION["purchaseid"];

    // STAP 2: Purchaseline-record aanmaken
    try
    {
        $sQuery = "INSERT INTO `purchaseline` (`purchaseid`, `productid`, `price`, `quantity`)
                            VALUES (:pur_purchaseid, :prod_productid, :prod_price, :pur_quantity)";
        $oStmt = $db->prepare($sQuery);
        $oStmt->bindValue(":pur_purchaseid", $pur_purchaseid);
        $oStmt->bindValue(":prod_productid", $prod_productid);
        $oStmt->bindValue(":prod_price", $prod_price);
        $oStmt->bindValue(":pur_quantity", $pur_quantity);
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
    <title>Bestelling opgeslagen</title>
    <link rel="stylesheet" type="text/css" href="company.css">
</head>
<body>

    <h2>Bestelling is opgeslagen</h2>
    <h2>Je kan een nieuw product aan de bestelling toevoegen</h2>

    <p><a href="pur-crud-add.php">Terug naar het productoverzicht</a></p>

</body>
</html>
<?php

    // test_input zorgt voor het opschonen van een veld in een formulier.
    function test_input($inpData)
    {
        $inpData = trim($inpData);
        $inpData = stripslashes($inpData);
        $inpData = htmlspecialchars($inpData);
        return $inpData;
    }

?>