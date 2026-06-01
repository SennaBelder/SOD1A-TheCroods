<!DOCTYPE html>
<html lang="nl"> 
<head>
	 <meta charset="UTF-8">
	 <title>Categorie toevoegen</title>
	 <link rel="stylesheet" type="text/css" href="company.css">  
</head>

<body>
    <?php
        session_start();
        if (!isset($_POST["submt-sel-cat-add"]))
        {
            if ((isset($_SESSION["chk_cat_insert"]) && $_SESSION["chk_cat_insert"]))
            {
                header("Refresh: 4, url=cat-crud-get.php");
                echo "<h2>Je bent hier niet op de juiste manier gekomen!</h2>";
                exit();
            }
        }
        echo "<header class='spacebelowabove'>";
		echo "<h1>Categorie toevoegen</h1>";
		include "nav.html";
	    echo "</header>";
    ?>
   
    <main class="centering">
        <h2 class="spacebelowabove">Toevoegen categorie</h2>
        <form action="cat-crud-adding.php" method="post" class="tabledisp">

            <fieldset class="tbodyflex">
                <label for="cat_name">Categorie naam : </label>
                <input type="text" name="cat_name" required >
            </fieldset>

            <fieldset class="tbodyflex, spacebelowabove">
                <button type="submit" formaction="cat-crud-get.php">Breek af</button>&nbsp;&nbsp;
                <input type="submit" value="Sla op" name="cat_applyinsert">
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
