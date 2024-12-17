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
        
        $query = "INSERT INTO gezinnen (achternaam, postcode, adres, volwassenen, kinderen, babies) 
                  VALUES ('$achternaam', '$postcode', '$adres', '$volwassenen', '$kinderen', '$babies')";

        if (!mysqli_query($connection, $query)) {
            die("Fout bij het invoegen van gezin: " . mysqli_error($connection));
        }

        $gezin_id = mysqli_insert_id($connection);

        if (!empty($_POST['allergenen'])) {
            $allergenen = explode(",", $_POST['allergenen']);
            foreach ($allergenen as $allergeen) {
                $allergeen = mysqli_real_escape_string($connection, trim($allergeen));
                $allergen_query = "INSERT INTO allergenen (gezin_id, allergeen) VALUES ('$gezin_id', '$allergeen')";
                if (!mysqli_query($connection, $allergen_query)) {
                    die("Fout bij het invoegen van allergenen: " . mysqli_error($connection));
                }
            }
        }
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
              <li class="button">
              <a href="inlog2.php" class="background-3">
                  <span class="login">Logout</span>
              </a>
          </li>
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
    <table>
    <thead>
        <tr>
            <th>Postcode</th>
            <th>Achternaam</th>
            <th>Adres</th>
            <th>Volwassenen</th>
            <th>Kinderen</th>
            <th>Babies</th>
            <th>Allergenen</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $query = "SELECT g.id, g.achternaam, g.postcode, g.adres, g.volwassenen, g.kinderen, g.babies, g.allergenen 
                  FROM gezinnen g
                  LEFT JOIN allergenen a ON g.id = g.allergenen_id";

        if (isset($_GET['search']) && $_GET['search'] != '') {
            $filtervalue = mysqli_real_escape_string($connection, $_GET['search']);
            $query .= " WHERE CONCAT(g.achternaam, g.postcode, g.adres, g.volwassenen, g.kinderen, g.babies) LIKE '%$filtervalue%'";
        }

        $result = mysqli_query($connection, $query);

        if (!$result) {
            die("Query mislukt: " . mysqli_error($connection)); 
        }

        $gezin_data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $gezin_data[$row['id']]['achternaam'] = htmlspecialchars($row['achternaam']);
            $gezin_data[$row['id']]['postcode'] = htmlspecialchars($row['postcode']);
            $gezin_data[$row['id']]['adres'] = htmlspecialchars($row['adres']);
            $gezin_data[$row['id']]['volwassenen'] = htmlspecialchars($row['volwassenen']);
            $gezin_data[$row['id']]['kinderen'] = htmlspecialchars($row['kinderen']);
            $gezin_data[$row['id']]['babies'] = htmlspecialchars($row['babies']);
            $gezin_data[$row['id']]['allergenen'][] = htmlspecialchars($row['allergenen']);
        }

        foreach ($gezin_data as $gezin) {
            echo "<tr>
                    <td>" . $gezin['postcode'] . "</td>
                    <td>" . $gezin['achternaam'] . "</td>
                    <td>" . $gezin['adres'] . "</td>
                    <td>" . $gezin['volwassenen'] . "</td>
                    <td>" . $gezin['kinderen'] . "</td>
                    <td>" . $gezin['babies'] . "</td>
                    <td>" . implode(", ", $gezin['allergenen']) . "</td>
                  </tr>";
        }
        ?>
    </tbody>
</table>


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
                <input type="text" name="allergenen" id="allergenen"><br><br>


                <button type="submit" class="btn btn-success">Opslaan</button>
            </form>
        </div>
    </div>

    <?php mysqli_close($connection); ?>
</body>
</html>
