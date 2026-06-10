<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="company.css">
  <title>Mijn bestellingen</title>
</head>
<body>

<?php

include "underconstruct.php";
require_once 'dbconnect.php';


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'klant') {
    // header('Location: scripts/dummy_client_login.php');
    // exit;
}
$klant_id = 1; // ← tijdelijk, vul een bestaand client ID in
// $klant_id = $_SESSION['user_id'];

$sql = "
    SELECT 
        pu.id             AS bestelnummer,
        pu.purchasedate   AS besteldatum,
        pu.delivered      AS afgeleverd,
        pr.productname    AS productnaam,
        pl.price          AS prijs,
        pl.quantity       AS aantal
    FROM purchase pu
    JOIN purchaseline pl ON pl.purchaseid = pu.id
    JOIN product pr      ON pr.id         = pl.productid
    WHERE pu.clientid = :klant_id
    ORDER BY pu.purchasedate DESC, pu.id
";

$stmt = $db->prepare($sql);
$stmt->execute([':klant_id' => $klant_id]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Mijn bestellingen</h2>

<?php if (count($rows) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Bestelnummer</th>
                <th>Besteldatum</th>
                <th>Afgeleverd</th>
                <th>Productnaam</th>
                <th>Prijs</th>
                <th>Aantal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['bestelnummer']) ?></td>
                <td><?= htmlspecialchars($row['besteldatum']) ?></td>
                <td><?= $row['afgeleverd'] === 'J' ? '✔ Ja' : '✘ Nee' ?></td>
                <td><?= htmlspecialchars($row['productnaam']) ?></td>
                <td>€ <?= number_format($row['prijs'], 2, ',', '.') ?></td>
                <td><?= htmlspecialchars($row['aantal']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php else: ?>
    <p>Je hebt nog geen bestellingen geplaatst.</p>
<?php endif; ?>

</body>
</html>
