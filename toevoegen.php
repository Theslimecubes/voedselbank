<?php

$connection = mysqli_connect("localhost", "root", "", "voedselbankdb");

if (!$connection) {
    die("Verbinding met database mislukt: " . mysqli_connect_error());
}


$naam = mysqli_real_escape_string($connection, $_POST['naam']);
$beschrijving = mysqli_real_escape_string($connection, $_POST['beschrijving']);
$houdbaarheidsdatum = mysqli_real_escape_string($connection, $_POST['houdbaarheidsdatum']);
$categorie_id = intval($_POST['categorie_id']);
$aantal = intval($_POST['aantal']);


$query = "INSERT INTO producten (naam, beschrijving, houdbaarheidsdatum, categorie_id, aantal) VALUES ('$naam', '$beschrijving', '$houdbaarheidsdatum', $categorie_id, $aantal)";

if (mysqli_query($connection, $query)) {
    echo "Product succesvol toegevoegd.";
    header("Location: searchbox.php");
    exit();
} else {
    echo "Fout bij toevoegen: " . mysqli_error($connection);
}

mysqli_close($connection);
?>

