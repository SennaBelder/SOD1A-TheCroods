<!DOCTYPE html>
<html lang="nl"> 
<head>
	 <meta charset="UTF-8">
	 <title>Bread Company</title>
	 <link rel="stylesheet" type="text/css" href="company.css">  
</head>

<body>
	<header>
		<h1>Welkom bij de Bread Company</h1>
		<?php
			session_start(); 
			include "nav.html";
		?>
	</header>
 	<?php
	require_once "dbconnect.php";
	try {
		$sQuery = "SELECT * FROM category";
		$oStmt = $db->prepare($sQuery);
		$oStmt->execute();

		echo "<p>&nbsp;</p><h2 class='centercell'>Overzicht Categorieën</h2><p>&nbsp;</p>";
		if ($oStmt->rowCount() > 0) {
			echo '<div class="centerflex"><table class="tabledisp2">';
			echo '<thead>';
			echo '<td>Cat.nr.</td>';
			echo '<td>Categorie naam</td>';
			echo '</thead>';
			while ($aRow = $oStmt->fetch(PDO::FETCH_ASSOC)) {
				echo '<tr>';
				echo '<td>' . $aRow['ID'] . '</td>';
				echo '<td>' . $aRow['name'] . '</td>';
				echo '</tr>';
			}
			echo '</table></div>';
		} else {
			echo 'Helaas, geen gegevens bekend';
		}
	} catch (PDOException $e) {
		$sMsg = '<p> 
					Regelnummer: ' . $e->getLine() . '<br /> 
					Bestand: ' . $e->getFile() . '<br /> 
					Foutmelding: ' . $e->getMessage() . ' 
				</p>';

		trigger_error($sMsg);
	}
	$db = null;
	?>


</body>
</html>