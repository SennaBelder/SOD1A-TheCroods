<!DOCTYPE html>
<html lang="nl"> 
<head>
	 <meta charset="UTF-8">
	 <title>Verwijder land</title>
	 <link rel="stylesheet" type="text/css" href="company.css">  
</head>

<body>
    <?php
        // Controleren of de gebruiker van cou-crud-get.php afkomt met delete knop
        if (!isset($_POST["submt-sel-cou-del"]))
        {
            header("Refresh: 4, url=cou-crud-get.php");
            echo "<h2>Je bent hier niet op de juiste manier gekomen!</h2>";
            exit();
        }

        // Formulierveld ophalen en opschonen
        $cou_pk = test_input($_POST["sel-cou-pk"]);
        
        // Het id moet nummeriek zijn
        if (!is_numeric($cou_pk))
        {
            header("Refresh: 4, url=cou-crud-get.php");
            echo "<h2>Je moet een nummer opgeven!!</h2>";
            exit();
        }

        // Controleren of land aanwezig is
        require_once "dbconnect.php";

        try 
        {
            $sQuery = "SELECT * FROM country WHERE idcountry = :cou_pk";
            $oStmt = $db->prepare($sQuery);
            $oStmt->bindValue(":cou_pk", $cou_pk);
            $oStmt->execute();

            if ($oStmt->rowCount() <> 1) 
            {
                header("Refresh: 4, url=cou-crud-get.php");
                echo "<h2>Het opgegeven land nummer bestaat niet!</h2>";
                exit();
            }
        } catch (PDOException $e) 
        {
            $sMsg = '<p> 
                        Regelnummer: ' . $e->getLine() . '<br /> 
                        Bestand: ' . $e->getFile() . '<br /> 
                        Foutmelding: ' . $e->getMessage() . ' 
                    </p>';
    
            trigger_error($sMsg);
            die();
        }

        // Controleren of land NIET in leveranciers of klanten hoort
        // Eerst checken in supplier tabel
        try 
        {
            $sQuery = "SELECT * FROM supplier WHERE countryid = :cou_pk";
            $oStmt = $db->prepare($sQuery);
            $oStmt->bindValue(":cou_pk", $cou_pk);
            $oStmt->execute();

            if ($oStmt->rowCount() > 0) 
            {
                header("Refresh: 4, url=cou-crud-get.php");
                echo "<h2>Dit land kan niet verwijderd worden omdat deze nog bij " . $oStmt->rowCount() . " leverancier(s) hoort!</h2>";
                exit();
            }
        } catch (PDOException $e) 
        {
            $sMsg = '<p> 
                        Regelnummer: ' . $e->getLine() . '<br /> 
                        Bestand: ' . $e->getFile() . '<br /> 
                        Foutmelding: ' . $e->getMessage() . ' 
                    </p>';
    
            trigger_error($sMsg);
            die();
        }

        // Ook checken in client tabel
        try 
        {
            $sQuery = "SELECT * FROM client WHERE country = :cou_pk";
            $oStmt = $db->prepare($sQuery);
            $oStmt->bindValue(":cou_pk", $cou_pk);
            $oStmt->execute();

            if ($oStmt->rowCount() > 0) 
            {
                header("Refresh: 4, url=cou-crud-get.php");
                echo "<h2>Dit land kan niet verwijderd worden omdat deze nog bij " . $oStmt->rowCount() . " klant(en) hoort!</h2>";
                exit();
            }
        } catch (PDOException $e) 
        {
            $sMsg = '<p> 
                        Regelnummer: ' . $e->getLine() . '<br /> 
                        Bestand: ' . $e->getFile() . '<br /> 
                        Foutmelding: ' . $e->getMessage() . ' 
                    </p>';
    
            trigger_error($sMsg);
            die();
        }

        // Sla de PK op in SESSION voor beveiliging
        session_start();
        $_SESSION["delete_cou_pk"] = $cou_pk;

        // Zet header op de pagina
        echo "<header class='spacebelowabove'>";
		echo "<h1>Land verwijderen</h1>";
		include "nav.html";
	    echo "</header>";

        // Haal nu de gegevens op van het record (nog een keer ophalen van begin)
        $sQuery = "SELECT * FROM country WHERE idcountry = :cou_pk";
        $oStmt = $db->prepare($sQuery);
        $oStmt->bindValue(":cou_pk", $cou_pk);
        $oStmt->execute();
        $dataCountry = $oStmt->fetch(PDO::FETCH_ASSOC);
    ?>
     
    <main class="centering">
        <h2 class="spacebelowabove">Verwijderen land</h2>
        <form action="cou-crud-delete.php" method="post" class="tabledisp">
            <input type="text" name="cou_pk" readonly value="<?php echo $cou_pk; ?>" >

            <fieldset class="tbodyflex">
                <label for="cou_name">Land naam : </label>
                <input type="text" name="cou_name" readonly value="<?php echo $dataCountry["name"]; ?>" >
            </fieldset>

            <fieldset class="tbodyflex">
                <label for="cou_code">Land code : </label>
                <input type="text" name="cou_code" readonly value="<?php echo $dataCountry["code"]; ?>" >
            </fieldset>

            <fieldset class="tbodyflex, spacebelowabove">
                <button type="submit" formaction="cou-crud-get.php">Breek af</button>&nbsp;&nbsp;
                <input type="submit" value="Verwijder" name="cou_applydelete">
            </fieldset>
        </form>
    </main>

    
    <?php
    // Hier komen alle functies te staan

    // test_input zorgt voor het opschonen van een veld in een formulier.
    function test_input($inpData)
    {
        $inpData = trim($inpData);
        $inpData = stripslashes($inpData);
        $inpData = htmlspecialchars($inpData);
        return $inpData;
    }

    ?>    

</body>
</html>
