<!DOCTYPE html>
<html lang="nl"> 
<head>
	 <meta charset="UTF-8">
	 <title>Categorie toevoegen verwerking</title>
	 <link rel="stylesheet" type="text/css" href="company.css">  
</head>

<body>
    <?php
        session_start();
        // Controleer of de gebruiker van het toevoegformulier afkomt
        if (!isset($_POST["cat_applyinsert"]))
        {
            header("Refresh: 4, url=cat-crud-get.php");
            echo "<h2>Je bent hier niet op de juiste manier gekomen!</h2>";
            exit();
        }

        // Haal formulier veld binnen
        $cat_name = test_input($_POST["cat_name"]);

        // Set SESSION variable voor controle
        $_SESSION["chk_cat_insert"] = true;

        // Controleer of naam ingevuld is en alleen letters/spaties bevat
        if (empty($cat_name) || !check_alfabet($cat_name))
        {
            header("Refresh: 4, url=cat-crud-add.php");
            echo "<h2>De categorie naam moet ingevuld zijn (met alleen letters en spaties)</h2>";
            exit();
        }

        // Controleer of categorie naam al bestaat
        require_once "dbconnect.php";
        try 
        {
            $sQuery = "SELECT * FROM category WHERE name = :cat_name";
            $oStmt = $db->prepare($sQuery);
            $oStmt->bindValue(":cat_name", $cat_name);
            $oStmt->execute();

            if ($oStmt->rowCount() > 0) 
            {
                header("Refresh: 4, url=cat-crud-add.php");
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
        // checking complete, release SESSION variable
        unset($_SESSION["chk_cat_insert"]);

        // Pas na alle controles bouw je de header op.
        try 
        {
            $sQuery = "INSERT INTO `category`(`name`) VALUES (:cat_name)";
            $oStmt = $db->prepare($sQuery);
            $oStmt->bindValue(":cat_name", $cat_name);
            $oStmt->execute();

            header("Refresh: 2, url=cat-crud-get.php");
            echo "<header class='spacebelowabove'>";
            echo "<h1>Categorie toevoegen</h1>";
            include "nav.html";
    	    echo "</header>";

            echo "<h2>De categorie is toegevoegd aan de database!</h2>";

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
