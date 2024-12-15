<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gebruikers</title>
    <link rel="stylesheet" href="producten.css">
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/light.css">
    <link rel="stylesheet" href="toevoegenknop.css">
</head>
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

        <table>
            <thead>
                <tr>
                    <th>Naam</th>
                    <th>Email</th>
                    <th>Password</th>
                    <th>Functie</th>
                </tr>
            </thead>
            <tbody>
                <?php
                
                if (isset($_GET['search']) && $_GET['search'] != '') {
                    $filtervalue = mysqli_real_escape_string($connection, $_GET['search']);
                }

                $result = mysqli_query($connection);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['naam']) . "</td>
                                <td>" . htmlspecialchars($row['email']) . "</td>
                                <td>" . htmlspecialchars($row['password']) . "</td>
                                <td>" . (isset($row['functie']) ? htmlspecialchars($row['functie']) : 'Geen Functie') . "</td>
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
            <h2>Gebruiker Toevoegen</h2>
            <form method="post" action="searchbox.php">
                <label for="naam">Naam:</label>
                <input type="text" name="naam" id="naam" required><br><br>

                <label for="password">Password:</label>
                <input type="text" name="password" id="password" required><br><br>

                <label for="email">Email:</label>
                <input type="text" name="email" id="email" required><br><br>

                <label for="functie">Functie:</label>
                <input type="number" name="functie" id="functie" required><br><br>

                <button type="submit" class="btn btn-success">Opslaan</button>
            </form>
        </div>
    </div>

    <?php mysqli_close($connection); ?>
</body>
</html>
