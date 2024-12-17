<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gezinnen</title>
    <link rel="stylesheet" href="producten.css">
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/light.css">
    <link rel="stylesheet" href="toevoegenknop.css">
</head>
<body>

<?php
    
    $connection = mysqli_connect("localhost", "root", "", "voedselbankdb");

    if (!$connection) {
        die("Verbinding met database mislukt: " . mysqli_connect_error());
    }

    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $achternaam = mysqli_real_escape_string($connection, $_POST['achternaam']);
        $postcode = mysqli_real_escape_string($connection, $_POST['postcode']);
        $adres = mysqli_real_escape_string($connection, $_POST['adres']);
        $volwassenen = mysqli_real_escape_string($connection, $_POST['volwassenen']);
        $kinderen = mysqli_real_escape_string($connection, $_POST['kinderen']);
        $babies = mysqli_real_escape_string($connection, $_POST['babies']);
        $babies = mysqli_real_escape_string($connection, $_POST['allergenen']);


        $query = "INSERT INTO gezinnen (achternaam, postcode, adres, volwassenen, kinderen, babies, allergenen) 
                  VALUES ('$achternaam', '$postcode', $adres, $volwassenen, $kinderen, $babies, $allergenen)";
        
        mysqli_query($connection, $query); 
    }
    ?>


    <nav class="navbar">
      <ul class="nav-list">
          <div class="image"></div>
          <li><a class="voedselbank-maas">Voedselbank Maaskantje</a></li>
          <li><a href="home.html">Home</a></li>
          <div class="dropdown">
            <button class="dropbtn">Overzicht</button>
            <div class="dropdown-content">
              <a href="searchbox.php">Producten</a>
              <a href="gebruikers.php">Gebruikers</a>
              <a href="gezinnen.php">Gezinnen</a>
              <a href="leverancieren.php">Leverancieren</a>
              <a href="voedselpakket.php">Voedsel pakketten</a>
            </div>
          </div>
          <li><a href="contact.html">Contact</a></li>
          <div class="button">
              <button href="inlog2.php" class="background-3"><span class="login">Login</span></button>
            </div>
              </div>
          </li>
      </ul>
  </nav>

  <div class="container mt-5">
    <form method="get"> 
        <div class="input-group mb-3"> 
            <input type="text" class="form-control" name="search" placeholder="Zoek hier..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"> 
            <button type="submit" class="btn btn-primary">Zoeken</button> 
        </div> 
    </form> 
    <div class="knop-container"> 
        <button class="toevoegen-knop" onclick="document.getElementById('modal').style.display='block'"> 
            <span class="icon">+</span> Toevoegen 
        </button> 
    </div>
</div>
        <table>
            <thead>
                <tr>
                    <th>Postcode</th>
                    <th>Achternaam</th>
                    <th>Adres</th>
                    <th>Volwassenen</th>
                    <th>Kinderen</th>
                    <th>Babies</th>
                </tr>
            </thead>
            <tbody>
                <?php

$query = "SELECT achternaam, postcode, adres, volwassenen, kinderen, babies, allergenen FROM gezinnen";

if (isset($_GET['search']) && $_GET['search'] != '') {
    $filtervalue = mysqli_real_escape_string($connection, $_GET['search']);
    $query .= " WHERE CONCAT(achternaam, postcode, adres, volwassenen, kinderen, babies, allergenen) LIKE '%$filtervalue%'";
}


$result = mysqli_query($connection, $query);


if (!$result) {
    die("Query mislukt: " . mysqli_error($connection)); 
}

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['postcode']) . "</td>
                                <td>" . htmlspecialchars($row['achternaam']) . "</td>
                                <td>" . htmlspecialchars($row['adres']) . "</td>
                                <td>" . htmlspecialchars($row['volwassenen']) . "</td>
                                <td>" . htmlspecialchars($row['kinderen']) . "</td>
                                <td>" . htmlspecialchars($row['babies']) . "</td>
                                <td>" . htmlspecialchars($row['allergenen']) . "</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Geen data gevonden...</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('modal').style.display='none'">&times;</span>
            <h2>Gezinnen Toevoegen</h2>
            <form method="post" action="gezinnentoevoegen.php">
                <label for="postcode">Postcode:</label>
                <input type="text" name="postcode" id="postcode" required><br><br>

                <label for="achternaam">Achternaam:</label>
                <input type="text" name="achternaam" id="achternaam" required><br><br>

                <label for="adres">Adres:</label>
                <input type="text" name="adres" id="adres" required><br><br>

                <label for="volwassenen">Volwassenen:</label>
                <input type="number" name="volwassenen" id="volwassenen"><br><br>

                <label for="kinderen">Kinderen:</label>
                <input type="number" name="kinderen" id="kinderen"><br><br>

                <label for="babies">Babies:</label>
                <input type="number" name="babies" id="babies"><br><br>

                <label for="allergenen">Allergenen:</label>
                <input type="text" name="allergenen" id="alergenen"><br><br>


                <button type="submit" class="btn btn-success">Opslaan</button>
            </form>
        </div>
    </div>

    <?php mysqli_close($connection); ?>
</body>
</html>
