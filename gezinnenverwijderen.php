<?php

$connection = mysqli_connect("localhost", "root", "", "voedselbankdb");

if (!$connection) {
    die("Verbinding met database mislukt: " . mysqli_connect_error());
}

$gebruikersnaam = $_GET['gebruikersnaam'];
$sql = "DELETE FROM gebruikers WHERE gebruikersnaam = '$gebruikersnaam'";
echo $sql;

if (mysqli_query($connection, $sql)) {
  echo "Record deleted successfully";
  header("Location: gezinnen.php");
} else {
  echo "Error deleting record: " . mysqli_error($connection);
}

?>