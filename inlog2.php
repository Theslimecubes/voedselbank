<!DOCTYPE html>
<html lang="en">
  <head>
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

      <form class="login-form" method= "POST">
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
        <button type="submit" class="login-btn" name="login_button">login</button>
      </form>
    </div>
  </body>
</html>
<?php 
$conn = mysqli_connect("localhost", "root", "", "voedselbankdb");
if(isset($_POST['login_button'])){
  $email=$_POST['email'];
  $password=$_POST['password'];
  $sql= "SELECT * FROM gebruikers WHERE email = '$email'";
  $result = mysqli_query($conn, $sql);
  while($row = mysqli_fetch_assoc($result)){
    $resultPassword = $row['wachtwoord'];
    if($password == $resultPassword){
      header('location: http://localhost/voedselbank/voedselbank-2/home.html');
      exit;
    }else{ 
      echo "error";
    }
  }
}
?>