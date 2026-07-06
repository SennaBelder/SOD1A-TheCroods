<!DOCTYPE html>
<html lang="nl">
<head>
	 <meta charset="UTF-8">
	 <title>Uitloggen</title>
	 <link rel="stylesheet" type="text/css" href="company.css">
</head>

<body>
    <?php
        session_start();
        // Alle SESSION variabelen weggooien en de sessie zelf ook opheffen.
        $_SESSION = array();
        session_unset();
        session_destroy();

        // Nieuwe (lege) sessie starten zodat de nav.html geen fouten geeft.
        session_start();

        header("Refresh: 3, url=index.php");
    ?>
	<header class="spacebelowabove">
		<?php include "nav.html"; ?>
	</header>
	<main class="centering">
		<h2>Je bent uitgelogd</h2>
		<p>Je wordt zo teruggestuurd naar de homepage.</p>
	</main>
</body>
</html>
