<?php

$connection = mysqli_connect("localhost", "root", "", "voedselbankdb");

if (!$connection) {
    die("Verbinding met database mislukt: " . mysqli_connect_error());
}

$id = $_GET['id'];
$sql = "DELETE FROM producten WHERE id= '$id'";
echo $sql;

if (mysqli_query($connection, $sql)) {
  echo "Record deleted successfully";
  header("Location: searchbox.php");
} else {
  echo "Error deleting record: " . mysqli_error($connection);
}

?>