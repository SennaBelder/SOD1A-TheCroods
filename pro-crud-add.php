<?php
session_start();
include "nav.html";

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

   
    $catStmt = $pdo->query("SELECT id, name FROM category");

   
    $supStmt = $pdo->query("SELECT id, company FROM supplier");

} catch (PDOException $e) {
    die("Fout: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product toevoegen</title>
     <link rel="stylesheet" href="company.css">
</head>
<body>

<h1>Product toevoegen</h1>

<form action="pro-crud-adding.php" method="post">

   
    Naam: <br>
    <input type="text" name="productname" required pattern="[A-Za-z ]+"><br><br>

    
    Ingredients: <br>
    <input type="text" name="ingredients" pattern="[A-Za-z0-9 ]*"><br><br>

   
    Allergens: <br>
    <input type="text" name="allergens" pattern="[A-Za-z0-9 ]*"><br><br>

    
    Prijs: <br>
    <input type="text" name="price" required pattern="[0-9]+,[0-9]{2}"><br>
    <small>Bijv: 12,34</small><br><br>

  
    Categorie: <br>
    <select name="categoryid" required>
        <option value="">-- kies categorie --</option>
        <?php while ($cat = $catStmt->fetch(PDO::FETCH_ASSOC)) { ?>
            <option value="<?= $cat['id'] ?>">
                <?= $cat['name'] ?>
            </option>
        <?php } ?>
    </select><br><br>

   
    Leverancier: <br>
    <select name="supplierid" required>
        <option value="">-- kies leverancier --</option>
        <?php while ($sup = $supStmt->fetch(PDO::FETCH_ASSOC)) { ?>
            <option value="<?= $sup['id'] ?>">
                <?= $sup['company'] ?>
            </option>
        <?php } ?>
    </select><br><br>

    
    <button type="submit">Sla op</button>

    <a href="pro-crud-get.php">
        <button type="button">Breek af</button>
    </a>

</form>

</body>
</html>