<?php if (isset($_SESSION["benJeErAl"]) && $_SESSION["benJeErAl"] && $_SESSION["SoortToegang"] == "Klant"): ?>
        <li class="main-item"><a href="#">Menu klant</a>
          <ul>
            <li><a href="pur-crud-add.php" class="scndlvl">Bestellen</a></li>
            <li><a href="pur-crud-shw.php" class="scndlvl">Mijn bestellingen</a></li>
          </ul>
        </li>
        <?php endif; ?>

        <?php if (isset($_SESSION["benJeErAl"]) && $_SESSION["benJeErAl"] && $_SESSION["SoortToegang"] == "Beheer"): ?>
        <li class="main-item"><a href="#">Menu beheerder</a>
          <ul>
            <li><a href="pur-crud-del.php" class="scndlvl">Bestellingen verwijderen</a></li>
          </ul>
        </li>
        <?php endif; ?>

        <li class="main-item">
          <?php if (isset($_SESSION["benJeErAl"]) && $_SESSION["benJeErAl"]): ?>
            <a href="logout.php">Uitloggen (<?php echo htmlspecialchars($_SESSION["wieBenJeDan"]); ?>)</a>
          <?php else: ?>
            <a href="#">Inloggen</a>
            <ul>
              <li><a href="scripts/dummy_client_login.php" class="scndlvl">Inloggen als klant</a></li>
              <li><a href="scripts/dummy_admin_login.php" class="scndlvl">Inloggen als beheerder</a></li>
            </ul>
          <?php endif; ?>