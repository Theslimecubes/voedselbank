
<!DOCTYPE html>
<html>
<head>
<title>Producten overzicht</title>
</head>


<link rel="stylesheet" href="producten.css">
<link rel="stylesheet" href="home.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/light.css">
<body>

<nav class="navbar">
      <ul class="nav-list">
          <div class="image"></div>
          <li><a class="voedselbank-maas">Voedselbank Maaskantje</a></li>
          <li><a href="home.html">Home</a></li>
          <div class="dropdown">
            <button class="dropbtn">Overzicht</button>
            <div class="dropdown-content">
              <a href="searchbox.php">Producten</a>
              <a href="gebruikers">Gebruikers</a>
              <a href="gezinnen">Gezinnen</a>
              <a href="leverancieren">Leverancieren</a>
              <a href="vpakket">Voedsel pakketten</a>
            </div>
          </div>
          <li><a href="contact">Contact</a></li>
          <div class="button">
              <button href="inlog.html" class="background-3"><span class="login">Login</span></button>
            </div>
              </div>
          </li>
      </ul>
  </nav>
    
    



<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
        
        
        <form actions="">
        <div class="input-group mb-3">

        <input type="text" class="form-control" value=""<?php if(isset($_GET['search'])){echo $_GET['search'];} ?> name="search" placeholder="search here. . .">
 
        <button type="submit" class="btn btn-primary">Zoeken</button>
   
         </div>
         </form>
      </div>

      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4 class="text-center"></h4>
            <div class="card-body">

            <div class="knop-container">
        <button class="toevoegen-knop">
            <span class="icon">+</span> Toevoegen
        </button>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Ean</th>
                <th>Naam</th>
                <th>Categorie ID</th>
                <th>Houdsbaarheid datum</th>
                <th>Aantal</th>
            </tr>
        </thead>
        <tbody id="">
          <?php 
          
          if(isset($_GET['search']))
          {
            $connection = mysqli_connect("localhost", "root", "", "voedselbankdb");
            $filtervalue = $_GET['search'];
            $filterdata = "SELECT * FROM producten WHERE CONCAT(id, naam, beschrijving, houdbaarheidsdatum, categorie_id) LIKE '%$filtervalue%'";
            $filterdata_run = mysqli_query($connection, $filterdata);

            if(mysqli_num_rows($filterdata_run) > 0)
            {
foreach($filterdata_run as $row)
{
  ?>
  <tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['naam']; ?></td>
    <td><?php echo $row['beschrijving']; ?></td>
    <td><?php echo $row['houdbaarheidsdatum']; ?></td>
    <td><?php echo $row['categorie_id']; ?></td>
  </tr>
  <?php
}
            }
            else
            {
?>
<tr>
  <td colspan="4">Geen data gevonden...</td>
</tr>
<?php
             }
          }

          ?>
            <!-- Event data wordt hier geladen -->
        </tbody>
    </table>
</div>
</div>

        </body>
</html>