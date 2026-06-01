<!DOCTYPE html>
<html lang="nl"> 
<head>
	 <meta charset="UTF-8">
	 <title>Land toevoegen verwerking</title>
	 <link rel="stylesheet" type="text/css" href="company.css">  
</head>

<body>
    <?php
        session_start();
        // Controleer of de gebruiker van het toevoegformulier afkomt
        if (!isset($_POST["cou_applyinsert"]))
        {
            header("Refresh: 4, url=cou-crud-get.php");
            echo "<h2>Je bent hier niet op de juiste manier gekomen!</h2>";
            exit();
        }

        // Haal formulier velden binnen
        $cou_name = test_input($_POST["cou_name"]);
        $cou_code = test_input($_POST["cou_code"]);

        // Set SESSION variable voor controle
        $_SESSION["chk_cou_insert"] = true;

        // Controleer of naam ingevuld is en alleen letters/spaties bevat
        if (empty($cou_name) || !check_alfabet($cou_name))
        {
            header("Refresh: 4, url=cou-crud-add.php");
            echo "<h2>De land naam moet ingevuld zijn (met alleen letters en spaties)</h2>";
            exit();
        }

        // Controleer of code ingevuld is en alleen letters/spaties bevat
        if (empty($cou_code) || !check_alfabet($cou_code))
        {
            header("Refresh: 4, url=cou-crud-add.php");
            echo "<h2>De land code moet ingevuld zijn (met alleen letters en spaties)</h2>";
            exit();
        }

        // Controleer of land naam al bestaat
        require_once "dbconnect.php";
        try 
        {
            $sQuery = "SELECT * FROM country WHERE name = :cou_name";
            $oStmt = $db->prepare($sQuery);
            $oStmt->bindValue(":cou_name", $cou_name);
            $oStmt->execute();

            if ($oStmt->rowCount() > 0) 
            {
                header("Refresh: 4, url=cou-crud-add.php");
                echo "<h2>Deze land naam bestaat al in de database!</h2>";
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

        // Controleer of land code al bestaat
        try 
        {
            $sQuery = "SELECT * FROM country WHERE code = :cou_code";
            $oStmt = $db->prepare($sQuery);
            $oStmt->bindValue(":cou_code", $cou_code);
            $oStmt->execute();

            if ($oStmt->rowCount() > 0) 
            {
                header("Refresh: 4, url=cou-crud-add.php");
                echo "<h2>Deze land code bestaat al in de database!</h2>";
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
        unset($_SESSION["chk_cou_insert"]);

        // Pas na alle controles bouw je de header op.
        try 
        {
            $sQuery = "INSERT INTO `country`(`name`, `code`) VALUES (:cou_name, :cou_code)";
            $oStmt = $db->prepare($sQuery);
            $oStmt->bindValue(":cou_name", $cou_name);
            $oStmt->bindValue(":cou_code", $cou_code);
            $oStmt->execute();

            header("Refresh: 2, url=cou-crud-get.php");
            echo "<header class='spacebelowabove'>";
            echo "<h1>Land toevoegen</h1>";
            include "nav.html";
    	    echo "</header>";

            echo "<h2>Het land is toegevoegd aan de database!</h2>";

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
