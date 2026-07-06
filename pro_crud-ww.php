<?php
session_start();

$localhost = "localhost";
$username = "root";
$dbname = "the-croods";
$dbpassword = "";

$dsn = "mysql:host=$localhost;dbname=$dbname;charset=utf8mb4";
$pdo = new PDO($dsn, $username, $dbpassword);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $password = $_POST['password'];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    
    $sql = "UPDATE client SET password = :password WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':password' => $hashedPassword,
        ':id' => $_SESSION['id']
    ]);

    echo "Wachtwoord succesvol gewijzigd.";
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Wachtwoord wijzigen</title>
</head>
<body>

<form action="" method="post">
    <label>Nieuw wachtwoord:</label>
    <input type="password" name="password" required><br><br>

    <button type="submit">Wijzigen</button>
    <button type="button" onclick="window.location.href='pro-crud-get.php'">Cancel</button>
</form>

</body>
</html>