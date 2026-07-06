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
				$sQuery = "SELECT category.ID, category.name, COUNT(product.ID) AS aantalproducten,
				                  AVG(product.price) AS gemprijs
				           FROM category
				           LEFT JOIN product ON product.categoryid = category.ID AND product.isactive = 'J'
				           GROUP BY category.ID, category.name
				           ORDER BY category.name";
				$oStmt = $db->prepare($sQuery);
				$oStmt->execute();
				?>

				<p>&nbsp;</p>
				<h2 class='centercell'>Categorieën met gemiddelde productprijs</h2>
				<p>&nbsp;</p>

				<?php
				if ($oStmt->rowCount() > 0) {
					echo '<table class="tabledisp2">';
					echo '<thead>';
					echo '<td>Categorie</td>';
					echo '<td>Aantal producten</td>';
					echo '<td>Gemiddelde prijs</td>';
					echo '</thead>';
					while ($aRow = $oStmt->fetch(PDO::FETCH_ASSOC)) {
						echo '<tr>';
						echo '<td>' . htmlspecialchars($aRow['name']) . '</td>';
						echo '<td>' . $aRow['aantalproducten'] . '</td>';
						echo '<td>' . ($aRow['gemprijs'] !== null ? number_format($aRow['gemprijs'], 2) : '-') . '</td>';
						echo '</tr>';
					}
					echo '</table>';
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
	</div>

</body>
</html>
