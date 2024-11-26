
<!DOCTYPE html>
<html>
<head>
<title>Producten overzicht</title>
</head>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/light.css">
<link rel="stylesheet" href="producten.css">
<body>

   
    
    



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