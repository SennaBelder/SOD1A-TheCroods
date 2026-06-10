<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Klanten beheren</title>
    <link rel="stylesheet" type="text/css" href="company.css">
</head>

<body>
    <?php
    session_start();
    require_once "dbconnect.php";

    echo "<header class='spacebelowabove'>";
    echo "<h1>Klanten beheren</h1>";
    include "nav.html";
    echo "</header>";
    ?>

    <main class="centering">
        <h2 class="spacebelowabove">Klanteninformatie</h2>
        <p>Hieronder staat een overzicht van de bestaande klanten.</p>

        <?php
        try {
            $sQuery = "SELECT id, CONCAT(first_name, ' ', last_name) AS full_name, email, adress, zipcode, city, telephone
                       FROM client
                       ORDER BY id";
            $oStmt = $db->prepare($sQuery);
            $oStmt->execute();

            if ($oStmt->rowCount() > 0) {
                echo '<div class="centerflex"><table class="tabledisp2">';
                echo '<thead><tr>';
                echo '<td>Kl.nr.</td>';
                echo '<td>Naam</td>';
                echo '<td>E-mail</td>';
                echo '<td>Adres</td>';
                echo '<td>Postcode</td>';
                echo '<td>Woonplaats</td>';
                echo '<td>Telefoon</td>';
                echo '</tr></thead>';

                while ($aClient = $oStmt->fetch(PDO::FETCH_ASSOC)) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($aClient['id']) . '</td>';
                    echo '<td>' . htmlspecialchars($aClient['full_name']) . '</td>';
                    echo '<td>' . htmlspecialchars($aClient['email']) . '</td>';
                    echo '<td>' . htmlspecialchars($aClient['adress']) . '</td>';
                    echo '<td>' . htmlspecialchars($aClient['zipcode']) . '</td>';
                    echo '<td>' . htmlspecialchars($aClient['city']) . '</td>';
                    echo '<td>' . htmlspecialchars($aClient['telephone']) . '</td>';
                    echo '</tr>';
                }

                echo '</table></div>';
            } else {
                echo '<p>Er zijn nog geen klanten bekend.</p>';
            }
        } catch (PDOException $e) {
            trigger_error('Klantenoverzicht kon niet worden geladen: ' . $e->getMessage());
        }
        ?>

        <h2 class="spacebelowabove">Klant toevoegen</h2>
        <form action="cli-crud-adding.php" method="post" class="tabledisp">
            <fieldset class="tbodyflex">
                <label for="first_name">Voornaam klant : </label>
                <input type="text" name="first_name" required>
            </fieldset>
            <fieldset class="tbodyflex">
                <label for="last_name">Achternaam klant : </label>
                <input type="text" name="last_name" required>
            </fieldset>
            <fieldset class="tbodyflex">
                <label for="email">E-mail : </label>
                <input type="email" name="email" required>
            </fieldset>
            <fieldset class="tbodyflex">
                <label for="adress">Adres klant : </label>
                <input type="text" name="adress">
            </fieldset>
            <fieldset class="tbodyflex">
                <label for="zipcode">Postcode : </label>
                <input type="text" name="zipcode">
            </fieldset>
            <fieldset class="tbodyflex">
                <label for="city">Woonplaats : </label>
                <input type="text" name="city" required>
            </fieldset>
            <fieldset class="tbodyflex">
                <label for="state">Provincie : </label>
                <input type="text" name="state" required>
            </fieldset>
            <fieldset class="tbodyflex">
                <label for="country">Land : </label>
                <select name="country" required>
                    <?php
                    try {
                        $sQueryCountry = "SELECT idcountry, name FROM country ORDER BY name";
                        $oStmtCountry = $db->prepare($sQueryCountry);
                        $oStmtCountry->execute();

                        while ($aCountry = $oStmtCountry->fetch(PDO::FETCH_ASSOC)) {
                            echo '<option value="' . (int) $aCountry['idcountry'] . '">' . htmlspecialchars($aCountry['name']) . '</option>';
                        }
                    } catch (PDOException $e) {
                        trigger_error('Landenlijst kon niet worden geladen: ' . $e->getMessage());
                    }
                    ?>
                </select>
            </fieldset>
            <fieldset class="tbodyflex">
                <label for="telephone">Telefoon : </label>
                <input type="text" name="telephone">
            </fieldset>
            <fieldset class="tbodyflex">
                <label for="pswrd">Wachtwoord : </label>
                <input type="password" name="pswrd" required>
            </fieldset>
            <fieldset class="tbodyflex, spacebelowabove">
                <button type="submit" formaction="cli-crud-shw.php">Terug</button>&nbsp;&nbsp;
                <input type="submit" value="Verwerk" name="cli_applyinsert">
            </fieldset>
        </form>
    </main>
</body>

</html>