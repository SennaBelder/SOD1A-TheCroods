<?php
session_start();

if (!isset($_SESSION['admin'])) {
    die("Access Denied");
} else {
    echo "Welkom, admin!";
}

$host = "localhost";
$dbname = "the-croods";
$username = "root";
$dbpassword = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Databasefout: " . $e->getMessage());
}

$id = $_GET['id'] ?? '';

if (empty($id)) {
    die("Geen client ID gevonden.");
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $currentPassword = $_POST["current_password"] ?? "";
    $newPassword = $_POST["password"] ?? "";
    $confirmPassword = $_POST["confirm_password"] ?? "";

    $stmt = $pdo->prepare("SELECT password FROM client WHERE id = :id");
    $stmt->execute([":id" => $id]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$client) {
        $message = "<p style='color:red'>Client niet gevonden.</p>";
    } elseif ($newPassword !== $confirmPassword) {
        $message = "<p style='color:red'>Nieuwe wachtwoorden komen niet overeen.</p>";
    } elseif (!empty($client["password"]) && !password_verify($currentPassword, $client["password"])) {
        $message = "<p style='color:red'>Huidig wachtwoord is onjuist.</p>";
    } elseif (!empty($client["password"]) && password_verify($newPassword, $client["password"])) {
        $message = "<p style='color:red'>Nieuw wachtwoord mag niet hetzelfde zijn als het huidige wachtwoord.</p>";
    } else {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("UPDATE client SET password = :password WHERE id = :id");
        $stmt->execute([
            ":password" => $hashedPassword,
            ":id" => $id
        ]);

        $message = "
        <div style='padding:15px; background:#d4edda; border:1px solid #28a745; color:#155724; width:400px; margin:20px auto; text-align:center;'>
            <h3>Wachtwoord succesvol gewijzigd!</h3>
            <p>U wordt automatisch doorgestuurd naar de homepage.</p>
            <a href='index.php'>
                <button>Terug naar homepage</button>
            </a>
        </div>";

        header("refresh:3;url=index.php");
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Wachtwoord wijzigen</title>
</head>
<body>

<h1>Wachtwoord wijzigen</h1>

<?= $message ?>

<?php if (empty($message) || strpos($message, 'succesvol') === false) { ?>
<form method="post">
    <label>Huidig wachtwoord:</label><br>
    <input type="password" name="current_password" required><br><br>

    <label>Nieuw wachtwoord:</label><br>
    <input type="password" name="password" required><br><br>

    <label>Herhaal nieuw wachtwoord:</label><br>
    <input type="password" name="confirm_password" required><br><br>

    <button type="submit">Wijzigen</button>
    <a href="index.php">Annuleren</a>
</form>
<?php } ?>

</body>
</html>