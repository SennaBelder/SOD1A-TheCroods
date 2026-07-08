<?php
session_start();


if (!isset($_SESSION['admin'])) {
    die("Access Denied");
}

$username = "root";
$password = "";
$dbname = "the-croods";
$dsn = "mysql:host=localhost;dbname=$dbname;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $id = $_GET['id'] ?? $_POST['id'] ?? null;

    if ($id === null) {
        die("Geen product ID meegegeven.");
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $sql = "DELETE FROM product WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        header("Location: pro-crud-get.php");
        exit;
    }

    $sql = "SELECT productname, price FROM product WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        die("Product niet gevonden.");
    }

} catch (PDOException $e) {
    die("Fout: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Product verwijderen</title>
</head>
<body>

<h1>Product verwijderen</h1>

<p>Weet je zeker dat je dit product wilt verwijderen?</p>

<p>ID: <?php echo htmlspecialchars($id); ?></p>
<p>Naam: <?php echo htmlspecialchars($product['productname']); ?></p>
<p>Prijs: €<?php echo htmlspecialchars($product['price']); ?></p>

<form method="POST" action="pro-crud-del.php">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
    <button type="submit">Verwijder</button>
</form>

<br>




</body>
</html>