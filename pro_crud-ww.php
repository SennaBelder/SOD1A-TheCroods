<?php
session_start();

$localhost = "localhost";
$username = "root";
$dbname = "the-croods";
$password = "";

$dsn = "mysql:host=$localhost;dbname=$dbname;charset=utf8mb4";
$pdo = new PDO($dsn, $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (password['current_password'], $hashed_password)) {
    if ($_POST['new_password'] === $_POST['confirm_password']) {
    } else {
        die("Wachtwoorden komen niet overeen");
    }
} else {
    die("Ongeldig wachtwoord");
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form action="password.php" method="post">
    <label for="password"></label>
    huidige wachtwoord: <input type="text" id="current_password" name="current_password" required><br>
     nieuwe wachtwoord: <input type="text" id="new_password" name="new_password" required><br>
     herhaal nieuwe wachtwoord: <input type="text" id="confirm_password" name="confirm_password" required><br>
    
    <button type="submit">wijzigen</button>
    <button type="button" onclick="window.location.href='pro-crud-get.php'">Cancel</button>

</form>
</body>
</html>