<!DOCTYPE html>
<html lang="nl"> 
<head>
	 <meta charset="UTF-8">
	 <title>Categorie wijzigen verwerking</title>
	 <link rel="stylesheet" type="text/css" href="company.css">  
</head>

<body>
    <?php
        // Controleren of de gebruiker van het wijzigformulier afkomt
        if (!isset($_POST["cat_applyupdate"]))
        {
            header("Refresh: 4, url=cat-crud-get.php");
            echo "<h2>Je bent hier niet op de juiste manier gekomen!</h2>";
            exit();
        }

        // Formuliervelden ophalen en opschonen
        $cat_pk = test_input($_POST["cat_pk"]);
        
        // Het id moet nummeriek zijn
        if (!is_numeric($cat_pk))
        {
            header("Refresh: 9, url=cat-crud-get.php");
            echo "<h2>Je moet een nummer opgeven!!</h2>";
            exit();
        }

        // Controleren of de PK nog hetzelfde is (beveiliging tegen hacken)
        session_start();
        if (!isset($_SESSION["update_cat_pk"]) || $_SESSION["update_cat_pk"] <> $cat_pk)
        {
            header("Refresh: 4, url=index.php");
            echo "<h2>HACKER HACKER HACKER</h2>";
            echo "Je hebt geprobeerd de werking van het programma te wijzigen!";
            exit();
        }
        
        // Haal de naam op uit formulier
        $cat_name = test_input($_POST["cat_name"]);

        // Controleer of naam ingevuld is en alleen letters/spaties bevat
        if (empty($cat_name) || !check_alfabet($cat_name))
        {
            header("Refresh: 4, url=cat-crud-upd.php");
            echo "<h2>De categorie naam moet ingevuld zijn (met alleen letters en spaties)</h2>";
            exit();
        }

        // Controleer of categorie naam al bestaat (MAAR NIET met dezelfde ID)
        require_once "dbconnect.php";
        try 
        {
            $sQuery = "SELECT * FROM category WHERE name = :cat_name AND ID <> :cat_pk";
            $oStmt = $db->prepare($sQuery);
            $oStmt->bindValue(":cat_name", $cat_name);
            $oStmt->bindValue(":cat_pk", $cat_pk);
            $oStmt->execute();

            if ($oStmt->rowCount() > 0) 
            {
                header("Refresh: 4, url=cat-crud-upd.php");
                echo "<h2>Deze categorie naam bestaat al in de database!</h2>";
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

        // Pas na alle controles bouw je de header op

        try 
        {
            $sQuery = "UPDATE `category` SET `name`= :cat_name WHERE ID = :cat_pk";
            $oStmt = $db->prepare($sQuery);
            $oStmt->bindValue(":cat_name", $cat_name);
            $oStmt->bindValue(":cat_pk", $cat_pk);
            $oStmt->execute();

            header("Refresh: 2, url=cat-crud-get.php");
            echo "<header class='spacebelowabove'>";
            echo "<h1>Categorie wijzigen</h1>";
            include "nav.html";
    	    echo "</header>";

            echo "<h2>De categorie is bijgewerkt in de database!</h2>";

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

        // Unset session
        unset($_SESSION["update_cat_pk"]);

    ?>
    
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
