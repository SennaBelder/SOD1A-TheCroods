<!DOCTYPE html>
<html lang="nl">
<head>
	 <meta charset="UTF-8">
	 <title>Categorieën - gemiddelde prijs</title>
	 <link rel="stylesheet" type="text/css" href="company.css">
</head>

<body>
	<header class="spaceabovebelow">
		<?php
			session_start();
			include "nav.html";
		?>
	</header>
	<div class="centerflex, spaceabovebelow">
		<?php
			require_once "dbconnect.php";

			try {
				// eerst alle categorieen ophalen
				$sQuery = "SELECT ID, name FROM category ORDER BY name";
				$oStmt = $db->prepare($sQuery);
				$oStmt->execute();
				$aCategorieen = $oStmt->fetchAll(PDO::FETCH_ASSOC);
			} catch (PDOException $e) {
				echo $e->getMessage();
			}
		?>

		<p>&nbsp;</p>
		<h2 class='centercell'>Categorieën met gemiddelde productprijs</h2>
		<p>&nbsp;</p>

		<?php
		if (count($aCategorieen) > 0) {
			echo '<table class="tabledisp2">';
			echo '<thead>';
			echo '<td>Categorie</td>';
			echo '<td>Aantal producten</td>';
			echo '<td>Gemiddelde prijs</td>';
			echo '</thead>';

			foreach ($aCategorieen as $cat) {
				$catID = $cat['ID'];

				try {
					$sQuery2 = "SELECT price FROM product WHERE categoryid = :catid AND isactive = 'J'";
					$oStmt2 = $db->prepare($sQuery2);
					$oStmt2->bindParam(':catid', $catID);
					$oStmt2->execute();
					$aProducten = $oStmt2->fetchAll(PDO::FETCH_ASSOC);
				} catch (PDOException $e) {
					echo $e->getMessage();
				}

				$aantal = count($aProducten);
				$totaal = 0;

				for ($i = 0; $i < $aantal; $i++) {
					$totaal = $totaal + $aProducten[$i]['price'];
				}

				if ($aantal > 0) {
					$gemiddelde = $totaal / $aantal;
					$gemiddelde = number_format($gemiddelde, 2);
				} else {
					$gemiddelde = '-';
				}

				echo '<tr>';
				echo '<td>' . $cat['name'] . '</td>';
				echo '<td>' . $aantal . '</td>';
				echo '<td>' . $gemiddelde . '</td>';
				echo '</tr>';
			}

			echo '</table>';
		} else {
			echo 'Helaas, geen gegevens bekend';
		}

		$db = null;
		?>
	</div>

</body>
</html>