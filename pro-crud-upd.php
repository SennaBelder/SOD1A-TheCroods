<<<<<<< Updated upstream
<?php

if (!isset($_GET['id'])) {
    die("geen id meegegeven");
}

$id = $_GET['id'];


$username = "root";
$password = "";
$dbname = "the-croods";
$dsn = "mysql:host=localhost;dbname=$dbname;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connectie mislukt: " . $e->getMessage());
}


$stmt = $pdo->prepare("SELECT * FROM product WHERE id = :id");
$stmt->execute(['id' => $id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$product) {
    die("Product niet gevonden");
}

?>

=======
>>>>>>> Stashed changes
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
<<<<<<< Updated upstream
    <title>Product Bewerken</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Product Bewerken</h1>
<button>    
        
<a href="pro-crud-get.php">Terug naar overzicht</a>
</button>
    <label>ID:</label><br>
    <input type="text" name="id" value="<?= $product['id'] ?>" readonly><br><br>

    <label>Productnaam:</label><br>
    <input type="text" name="productname" value="<?= $product['productname'] ?>" required><br><br>

    <label>Ingredients:</label><br>
    <input type="text" name="ingredients" value="<?= $product['ingredients'] ?>"><br><br>

    <label>Allergens:</label><br>
    <input type="text" name="allergens" value="<?= $product['allergens'] ?>"><br><br>

    <label>Prijs:</label><br>
    <input type="text" name="price" value="<?= $product['price'] ?>" required><br><br>

    <label>Categorie ID:</label><br>
    <input type="number" name="categoryid" value="<?= $product['categoryid'] ?>" required><br><br>

    <label>Supplier ID:</label><br>
    <input type="number" name="supplierid" value="<?= $product['supplierid'] ?>" required><br><br>

    <label>Actief:</label><br>
    <input type="text" value="<?= $product['isactive'] ?>" readonly><br><br>
    <button>
        <a href="pro-crud-update.php">Opslaan</a>
           </button>
           <button>
    <a href="pro-crud-get.php">Breek af</a>
    </button>
 
</form>

=======
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <button>wijzigen</button>
    <form action="wijzigen"></form>


    
>>>>>>> Stashed changes
</body>
</html>