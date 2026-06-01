<!DOCTYPE html>
<html lang="nl"> 
<head>
	 <meta charset="UTF-8">
	 <title>Categorie verwijderen verwerking</title>
	 <link rel="stylesheet" type="text/css" href="company.css">  
</head>

<body>
    <?php
        // Controleren of de gebruiker van het verwijderformulier afkomt
        if (!isset($_POST["cat_applydelete"]))
        {
            header("Refresh: 4, url=cat-crud-get.php");
            echo "<h2>Je bent hier niet op de juiste manier gekomen!</h2>";
            exit();
        }

        // Formulierveld ophalen en opschonen
        $cat_pk = test_input($_POST["cat_pk"]);
        
        // Het id moet nummeriek zijn
        if (!is_numeric($cat_pk))
        {
            header("Refresh: 4, url=cat-crud-get.php");
            echo "<h2>Je moet een nummer opgeven!!</h2>";
            exit();
        }

        // Controleren of de PK nog hetzelfde is (beveiliging tegen hacken)
        session_start();
        if (!isset($_SESSION["delete_cat_pk"]) || $_SESSION["delete_cat_pk"] <> $cat_pk)
        {
            header("Refresh: 4, url=index.php");
            echo "<h2>HACKER HACKER HACKER</h2>";
            echo "Je hebt geprobeerd de werking van het programma te wijzigen!";
            exit();
        }
        
        // Pas na alle controles bouw je de header op

        try 
        {
            require_once "dbconnect.php";
            $sQuery = "DELETE FROM `category` WHERE ID = :cat_pk";
            $oStmt = $db->prepare($sQuery);
            $oStmt->bindValue(":cat_pk", $cat_pk);
            $oStmt->execute();

            header("Refresh: 2, url=cat-crud-get.php");
            echo "<header class='spacebelowabove'>";
            echo "<h1>Categorie verwijderen</h1>";
            include "nav.html";
    	    echo "</header>";

            echo "<h2>De categorie is verwijderd uit de database!</h2>";

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
        unset($_SESSION["delete_cat_pk"]);

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

    ?>    

</body>
</html>
