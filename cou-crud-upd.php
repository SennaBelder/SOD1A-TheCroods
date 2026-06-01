<!DOCTYPE html>
<html lang="nl"> 
<head>
	 <meta charset="UTF-8">
	 <title>Land wijzigen</title>
	 <link rel="stylesheet" type="text/css" href="company.css">  
</head>

<body>
    <?php
        // Controleren of de gebruiker van cou-crud-get.php afkomt met het wijzig knop
        if (!isset($_POST["submt-sel-cou-upd"]))
        {
            header("Refresh: 4, url=cou-crud-get.php");
            echo "<h2>Je bent hier niet op de juiste manier gekomen!</h2>";
            exit();
        }

        // Formulierveld ophalen en opschonen
        $cou_pk = test_input($_POST["sel-cou-pk"]);
        
        // Het geselecteerde id moet nummeriek zijn
        if (!is_numeric($cou_pk))
        {
            header("Refresh: 4, url=cou-crud-get.php");
            echo "<h2>Je moet een nummer opgeven!!</h2>";
            exit();
        }

        // Controleren of de opgegeven Primary Key daadwerkelijk aanwezig is
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

        // Sla de PK op in SESSION voor beveiliging
        session_start();
        $_SESSION["update_cou_pk"] = $cou_pk;

        // Zet header op de pagina
        echo "<header class='spacebelowabove'>";
		echo "<h1>Land wijzigen</h1>";
		include "nav.html";
	    echo "</header>";

        // Haal nu de gegevens op van het record
        $dataCountry = $oStmt->fetch(PDO::FETCH_ASSOC);
    ?>
     
    <main class="centering">
        <h2 class="spacebelowabove">Wijzigen land</h2>
        <form action="cou-crud-update.php" method="post" class="tabledisp">
            <input type="text" name="cou_pk" readonly value="<?php echo $cou_pk; ?>" >

            <fieldset class="tbodyflex">
                <label for="cou_name">Land naam : </label>
                <input type="text" name="cou_name" required value="<?php echo $dataCountry["name"]; ?>" >
            </fieldset>

            <fieldset class="tbodyflex">
                <label for="cou_code">Land code : </label>
                <input type="text" name="cou_code" required maxlength="3" value="<?php echo $dataCountry["code"]; ?>" >
            </fieldset>

            <fieldset class="tbodyflex, spacebelowabove">
                <button type="submit" formaction="cou-crud-get.php">Breek af</button>&nbsp;&nbsp;
                <input type="submit" value="Opslaan" name="cou_applyupdate">
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

    // check_alfabet controleert of de input alleen uit letters en spaties bestaat.
    function check_alfabet($inpData)
    {
        if (preg_match("/^[a-zA-Z-' ]*$/",$inpData)) 
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    ?>    

</body>
</html>
