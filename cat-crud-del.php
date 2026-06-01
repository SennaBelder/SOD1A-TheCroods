<!DOCTYPE html>
<html lang="nl"> 
<head>
	 <meta charset="UTF-8">
	 <title>Verwijder categorie</title>
	 <link rel="stylesheet" type="text/css" href="company.css">  
</head>

<body>
    <?php
        // Controleren of de gebruiker van cat-crud-get.php afkomt met delete knop
        if (!isset($_POST["submt-sel-cat-del"]))
        {
            header("Refresh: 4, url=cat-crud-get.php");
            echo "<h2>Je bent hier niet op de juiste manier gekomen!</h2>";
            exit();
        }

        // Formulierveld ophalen en opschonen
        $cat_pk = test_input($_POST["sel-cat-pk"]);
        
        // Het id moet nummeriek zijn
        if (!is_numeric($cat_pk))
        {
            header("Refresh: 4, url=cat-crud-get.php");
            echo "<h2>Je moet een nummer opgeven!!</h2>";
            exit();
        }

        // Controleren of categorie aanwezig is
        require_once "dbconnect.php";

        try 
        {
            $sQuery = "SELECT * FROM category WHERE ID = :cat_pk";
            $oStmt = $db->prepare($sQuery);
            $oStmt->bindValue(":cat_pk", $cat_pk);
            $oStmt->execute();

            if ($oStmt->rowCount() <> 1) 
            {
                header("Refresh: 4, url=cat-crud-get.php");
                echo "<h2>Het opgegeven categorie nummer bestaat niet!</h2>";
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

        // Controleren of categorie NIET bij producten hoort (Foreign Key check)
        try 
        {
            $sQuery = "SELECT * FROM product WHERE category = :cat_pk";
            $oStmt = $db->prepare($sQuery);
            $oStmt->bindValue(":cat_pk", $cat_pk);
            $oStmt->execute();

            if ($oStmt->rowCount() > 0) 
            {
                header("Refresh: 4, url=cat-crud-get.php");
                echo "<h2>Deze categorie kan niet verwijderd worden omdat deze nog bij " . $oStmt->rowCount() . " product(en) hoort!</h2>";
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
        $_SESSION["delete_cat_pk"] = $cat_pk;

        // Zet header op de pagina
        echo "<header class='spacebelowabove'>";
		echo "<h1>Categorie verwijderen</h1>";
		include "nav.html";
	    echo "</header>";

        // Haal nu de gegevens op van het record (nog een keer ophalen van begin)
        $sQuery = "SELECT * FROM category WHERE ID = :cat_pk";
        $oStmt = $db->prepare($sQuery);
        $oStmt->bindValue(":cat_pk", $cat_pk);
        $oStmt->execute();
        $dataCategory = $oStmt->fetch(PDO::FETCH_ASSOC);
    ?>
     
    <main class="centering">
        <h2 class="spacebelowabove">Verwijderen categorie</h2>
        <form action="cat-crud-delete.php" method="post" class="tabledisp">
            <input type="text" name="cat_pk" readonly value="<?php echo $cat_pk; ?>" >

            <fieldset class="tbodyflex">
                <label for="cat_name">Categorie naam : </label>
                <input type="text" name="cat_name" readonly value="<?php echo $dataCategory["name"]; ?>" >
            </fieldset>

            <fieldset class="tbodyflex, spacebelowabove">
                <button type="submit" formaction="cat-crud-get.php">Breek af</button>&nbsp;&nbsp;
                <input type="submit" value="Verwijder" name="cat_applydelete">
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
