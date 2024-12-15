<?php

$connection = mysqli_connect("localhost", "root", "", "voedselbankdb");

if (!$connection) {
    die("Verbinding met database mislukt: " . mysqli_connect_error());
}


$naam = mysqli_real_escape_string($connection, $_POST['naam']);
$email = mysqli_real_escape_string($connection, $_POST['email']);
$password = mysqli_real_escape_string($connection, $_POST['password']);
$funtcie = intval($_POST['functie']);


$query = "INSERT INTO gebruikers (naam, email, password, functie) VALUES ('$naam', '$email', '$password', $functie)";

if (mysqli_query($connection, $query)) {
    echo "Gebruiker succesvol toegevoegd.";
    header("Location: gebruikers.php");
    exit();
} else {
    echo "Fout bij toevoegen: " . mysqli_error($connection);
}

mysqli_close($connection);
?>