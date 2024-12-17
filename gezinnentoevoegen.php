<?php

$connection = mysqli_connect("localhost", "root", "", "voedselbankdb");

if (!$connection) {
    die("Verbinding met database mislukt: " . mysqli_connect_error());
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $achternaam = mysqli_real_escape_string($connection, $_POST['achternaam']);
    $postcode = mysqli_real_escape_string($connection, $_POST['postcode']);
    $adres= mysqli_real_escape_string($connection, $_POST['adres']);
    $volwassenen= mysqli_real_escape_string($connection, $_POST['volwassenen']);
    $kinderen= mysqli_real_escape_string($connection, $_POST['kinderen']);
    $babies= mysqli_real_escape_string($connection, $_POST['babies']);
    $allergenen= mysqli_real_escape_string($connection, $_POST['allergenen']);
    
    if (empty($achternaam) || empty($postcode) || empty($adres) || 0>($volwassenen)  || 0>($kinderen) || 0>($babies) || empty($allergenen) ) {
        echo "Alle velden moeten ingevuld zijn.";
    } else {
        
        $query = "INSERT INTO gezinnen (achternaam, postcode, adres, volwassenen, kinderen, babies, allergenen) 
                  VALUES ('$achternaam', '$postcode', '$adres', '$volwassenen', '$kinderen', '$babies, $allergenen')";

       
        if (mysqli_query($connection, $query)) {
            
            echo "Data succesvol toegevoegd!";
        } else {
            
            echo "Fout bij toevoegen: " . mysqli_error($connection);
        }
    }
}


mysqli_close($connection);
?>

