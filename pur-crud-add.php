<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="company.css">
  <title>Add product</title>
</head>
<body>

<?php
include "underconstruct.php";

// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
// }

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'klant') {
    // header('Location: pur-crud-add.php');
    // exit;  // ← altijd exit na header redirect
}

require_once 'dbconnect.php';



$sql = "
    SELECT p.id, p.productname, c.name AS category_name, p.price
    FROM product p
    JOIN category c ON p.categoryid = c.id
    WHERE p.isactive = 'J'
    ORDER BY c.name, p.productname
";

$stmt = $db->query($sql);
$rows   = $stmt->fetchAll(PDO::FETCH_ASSOC);  // ← PDO-manier
?>

<h2>LET OP: je kan maar één product tegelijk bestellen</h2>

<?php if (count($rows) > 0): ?>   <!-- ← count() ipv num_rows -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Productnaam</th>
                <th>Categorie</th>
                <th>Prijs</th>
                <th>Aantal</th>
                <th>Actie</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $row): ?>   <!-- ← foreach over array -->
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['productname']) ?></td>
                <td><?= htmlspecialchars($row['category_name']) ?></td>
                <td class="price">€ <?= number_format($row['price'], 2, ',', '.') ?></td>
                <td>
                    <form action="pur-crud-adding.php" method="POST">
                        <input type="hidden" name="product_id" value="<?= (int)$row['id'] ?>">
                        <input type="number" name="aantal" value="1" min="1" required>
                </td>
                <td>
                        <button type="submit" class="btn-bestellen">Bestellen</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php else: ?>
    <p class="no-products">Er zijn momenteel geen actieve producten beschikbaar.</p>
<?php endif; ?>

</body>
</html>