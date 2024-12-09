<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welkom</title>
    <link rel="stylesheet" href="inlog.css">
  </head>
  <head>
    <link rel="stylesheet" type="text/css" href="inlog.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welkom</title>
    <link rel="stylesheet" href="inlog.css">
  </head>

  <body>
    <div class="login-container">
      <div class="logo">
        <img src="Images/Voedselbank-logo.png" alt="Logo">
      </div>

      <h1>Welkom</h1>

      <form class="login-form">
        <input 
          type="email" 
          class="input-field" 
          placeholder="email..." 
          name = "email"
          required
        >
        <input 
          type="password" 
          class="input-field" 
          placeholder="wachtwoord..." 
          name = "password"
          required
        >
        <button type="submit" href="home.html" class="login-btn" name="login_button">
          login
        </button>
      </form>
    </div>
  </body>
</html>
<?php 
$conn = mysqli_connect("localhost", "root","");
if(isset($_POST['login_button'])){
  $email=$_POST['email'];
  $password=$_POST['password'];
  $sql= "SELECT * FROM inlog-voedselbank.logindetails WHERE email = '$email'";
  $result = mysqli_query($conn,$sql);
  while($row = mysqli_fetch_assoc($result)){
    $resultPassword = $row['password'];
    if($password == $resultPassword){
      header('location:home.html');

    }else{ 


    }
  }

}
?>
