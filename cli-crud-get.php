<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Klantenoverzicht</title>
    <link rel="stylesheet" type="text/css" href="company.css">
</head>

<body>
    <header>
        <h1>Klantenoverzicht</h1>
        <?php
        session_start();
        include "nav.html";
        ?>
    </header>

    <?php
    require_once "dbconnect.php";

    try {
        $sQuery = "SELECT id, CONCAT(first_name, ' ', last_name) AS full_name, email, adress, zipcode, city, state, telephone
                   FROM client
                   ORDER BY id";

        $oStmt = $db->prepare($sQuery);
        $oStmt->execute();

        echo "<p>&nbsp;</p><h2 class='centercell'>Overzicht klanten</h2><p>&nbsp;</p>";

        if ($oStmt->rowCount() > 0) {
            echo '<div class="centerflex"><table class="tabledisp2">';
            echo '<thead><tr>';
            echo '<td>Kl.nr.</td>';
            echo '<td>Naam</td>';
            echo '<td>E-mail</td>';
            echo '<td>Adres</td>';
            echo '<td>Postcode</td>';
            echo '<td>Woonplaats</td>';
            echo '<td>Provincie</td>';
            echo '<td>Telefoon</td>';
            echo '<td>Acties</td>';
            echo '</tr></thead>';

            while ($aRow = $oStmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<tr>';
                echo '<td>' . (int) $aRow['id'] . '</td>';
                echo '<td>' . htmlspecialchars($aRow['full_name']) . '</td>';
                echo '<td>' . htmlspecialchars($aRow['email']) . '</td>';
                echo '<td>' . htmlspecialchars($aRow['adress']) . '</td>';
                echo '<td>' . htmlspecialchars($aRow['zipcode']) . '</td>';
                echo '<td>' . htmlspecialchars($aRow['city']) . '</td>';
                echo '<td>' . htmlspecialchars($aRow['state']) . '</td>';
                echo '<td>' . htmlspecialchars($aRow['telephone']) . '</td>';

                echo '<td>
                        <a href="pro_crud-ww.php?id=' . (int) $aRow['id'] . '">
                            <button>Wachtwoord wijzigen</button>
                        </a>
                      </td>';

                echo '</tr>';
            }

            echo '</table></div>';
        } else {
            echo 'Er zijn momenteel geen klanten beschikbaar.';
        }
    } catch (PDOException $e) {
        echo 'Klantenoverzicht kon niet worden geladen: ' . $e->getMessage();
    }

    $db = null;
    ?>
</body>

</html>