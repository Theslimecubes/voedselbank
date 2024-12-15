<?php

$connection = mysqli_connect("localhost", "root", "", "voedselbankdb");

if (!$connection) {
    die("Verbinding met database mislukt: " . mysqli_connect_error());
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $gebruikersnaam = mysqli_real_escape_string($connection, $_POST['gebruikersnaam']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $wachtwoord= mysqli_real_escape_string($connection, $_POST['wachtwoord']);
    $functie = intval($_POST['functie'])

    if (empty($gebruikersnaam) || empty($email) || empty($wachtwoord) || empty($functie)) {
        echo "Alle velden moeten ingevuld zijn.";
    } else {
        
        $query = "INSERT INTO gebruikers (gebruikersnaam, email, wachtwoord, functie) 
                  VALUES ('$gebruikersnaam', '$email', '$wachtwoord', '$functie')";

       
        if (mysqli_query($connection, $query)) {
            
            echo "Data succesvol toegevoegd!";
        } else {
            
            echo "Fout bij toevoegen: " . mysqli_error($connection);
        }
    }
}


mysqli_close($connection);
?>

