<!DOCTYPE html>
<html lang="nl"> 
<head>
	 <meta charset="UTF-8">
	 <title>Selecteer categorie</title>
	 <link rel="stylesheet" type="text/css" href="company.css">  
</head>

<body>
	<header class="spaceabovebelow">
		<h1>Welkom bij de Bread Company</h1>
		<?php
			session_start(); 
			include "nav.html";
		?>
	</header>
	<div class="centerflex, spaceabovebelow">
		<?php
			require_once "dbconnect.php";
			try {
				$sQuery = "SELECT * FROM category";
				$oStmt = $db->prepare($sQuery);
				$oStmt->execute();
				?>

				<p>&nbsp;</p>
				<h2 class='centercell'>Onderhoud categorieën</h2>
				<p>&nbsp;</p>

				<?php
				if ($oStmt->rowCount() > 0) {
					echo '<div class="flexverticalcenter">';

					// Knop om een nieuwe categorie toe te voegen
					echo '<form action="cat-crud-add.php" method="post">';
					echo '  <label for="submt-sel-cat-add">Toevoegen categorie &nbsp; </label>';
					echo '  <input type="submit" value="Voeg toe" name="submt-sel-cat-add" >';
					echo '</form>';
					echo '<p class="spacebelowabove">&nbsp;&nbsp;&nbsp;&nbsp;</p>';

					// Tabel met alle categorieën
					echo '<table class="tabledisp2">';
					echo '<thead>';
					echo '<td>ID</td>';
					echo '<td>Naam</td>';
					echo '<td>Acties</td>';
					echo '</thead>';
					while ($aRow = $oStmt->fetch(PDO::FETCH_ASSOC)) {
						echo '<tr><form action="cat-crud-upd.php" method="POST">';
						echo '<td><input type="number" readonly name="sel-cat-pk" value="' . $aRow['ID'] . '"></td>';
						echo '<td>' . $aRow['name'] . '</td>';
						echo '<td><input type="submit" value="Wijzig" name="submt-sel-cat-upd">&nbsp;&nbsp;';
						echo '<input type="submit" value="Verwijder" name="submt-sel-cat-del" formaction="cat-crud-del.php"></td>';
						echo '</form></tr>';
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
	</div>

</body>
</html>