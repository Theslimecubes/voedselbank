<?php

$connection = mysqli_connect("localhost", "root", "", "voedselbankdb");

if (!$connection) {
    die("Verbinding met database mislukt: " . mysqli_connect_error());
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $naam = mysqli_real_escape_string($connection, $_POST['naam']);
    $contactpersoon = mysqli_real_escape_string($connection, $_POST['contactpersoon']);
    $telefoon = mysqli_real_escape_string($connection, $_POST['telefoon']);
    $volgende_levering = mysqli_real_escape_string($connection, $_POST['volgende_levering']);

    if (empty($naam) || empty($contactpersoon) || empty($telefoon) || empty($volgende_levering)) {
        echo "Alle velden moeten ingevuld zijn.";
    } else {
        
        $query = "INSERT INTO leverancier (naam, contactpersoon, telefoon, volgende_levering) 
                  VALUES ('$naam', '$contactpersoon', '$telefoon', '$volgende_levering')";

       
        if (mysqli_query($connection, $query)) {
            
            echo "Data succesvol toegevoegd!";
        } else {
            
            echo "Fout bij toevoegen: " . mysqli_error($connection);
        }
    }
}


mysqli_close($connection);
?>


