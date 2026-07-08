<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dummy login beheerder</title>
</head>
<body>

<?php
require_once("../dbconnect.php");
session_start();

try {
    $query = $db->prepare("SELECT id FROM client WHERE isadmin = 'J'");
    $query->execute();

    if ($query->rowCount() > 0) {

        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $allclients = [];

        foreach ($result as $rij) {
            $allclients[] = $rij["id"];
        }

    } else {
        die("GEEN BEHEERDERS GEVONDEN!");
    }

} catch (PDOException $e) {

    die($e->getMessage());

}

$randomclient = $allclients[array_rand($allclients)];

try {

    $query = $db->prepare("SELECT * FROM client WHERE id = :randomclient");
    $query->bindValue(":randomclient", $randomclient);
    $query->execute();

    if ($query->rowCount() == 1) {

        $dataClient = $query->fetch(PDO::FETCH_ASSOC);

        $_SESSION["benJeErAl"] = true;
        $_SESSION["welkNummerIsDit"] = $randomclient;
        $_SESSION["wieBenJeDan"] = $dataClient["first_name"] . " " . $dataClient["last_name"];
        $_SESSION["SoortToegang"] = "Beheer";

        // BELANGRIJK
        $_SESSION["admin"] = true;
        $_SESSION["id"] = $randomclient;

        echo "<h1>Inloggen als BEHEERDER is gelukt</h1><br>";
        echo "<p>Inlognaam is : " . $_SESSION["wieBenJeDan"] . "</p>";
        echo "<p>Primary key van deze beheerder is: " . $_SESSION["welkNummerIsDit"] . "</p>";

        header("Refresh:2; url=../pro-crud-get.php");

    } else {

        die("INLOGGEN MISLUKT!");

    }

} catch (PDOException $e) {

    die($e->getMessage());

}
?>

</body>
</html>