<?php

$id = $_GET['id'];
$connection = mysqli_connect("localhost", "root", "", "voedselbankdb");

if (!$connection) {
    die("Verbinding met database mislukt: " . mysqli_connect_error());
}

$sql = "DELETE FROM gebruikers WHERE id = $id"; 

if (mysqli_query($connection, $sql)) {
    mysqli_close($connection);
    header('Location: gebruikers.php');
    exit;
} else {
    echo "Error deleting record";
}
?>








<!-- <?php

$connection = mysqli_connect("localhost", "root", "", "voedselbankdb");
$tableName="gebruikers";

if (!$connection) {
    die("Verbinding met database mislukt: " . mysqli_connect_error());
}

if(isset($_GET['delete']))
{
  $id= validate($_GET['delete']);
  $condition =['id'=>$id];
  $deleteMsg=delete_data($connection, $tableName, $condition);
  header("location:form.php");
}
function delete_data($connection, $tableName, $condition){
    $conditionData='';
    $i=0;
    foreach($condition as $index => $data){
        $and = ($i > 0)?' AND ':'';
         $conditionData .= $and.$index." = "."'".$data."'";
         $i++;
    }
  $query= "DELETE FROM ".$tableName." WHERE ".$conditionData;
  $result= $db->query($query);
  if($result){
    $msg="data was deleted successfully";
  }else{
    $msg= $db->error;
  }
  return $msg;
}
function validate($value) {
$value = trim($value);
$value = stripslashes($value);
$value = htmlspecialchars($value);
return $value;
}

mysqli_close($connection);
?> -->