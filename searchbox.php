<!DOCTYPE html>
<html>
<head>
    <title>Producten overzicht</title>
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
                    <a href="gebruikers">Gebruikers</a>
                    <a href="gezinnen">Gezinnen</a>
                    <a href="leverancieren">Leveranciers</a>
                    <a href="vpakket">Voedsel pakketten</a>
                </div>
            </div>
            <li><a href="contact">Contact</a></li>
            <div class="button">
                <button href="inlog.html" class="background-3"><span class="login">Login</span></button>
            </div>
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
                    <th>Ean</th>
                    <th>Naam</th>
                    <th>Categorie ID</th>
                    <th>Houdsbaarheid datum</th>
                    <th>Aantal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                
                $connection = mysqli_connect("localhost", "root", "", "voedselbankdb");

                
                $query = "SELECT * FROM producten";
                if (isset($_GET['search']) && $_GET['search'] != '') {
                    $filtervalue = mysqli_real_escape_string($connection, $_GET['search']);
                    $query .= " WHERE CONCAT(id, naam, beschrijving, houdbaarheidsdatum, categorie_id) LIKE '%$filtervalue%'";
                }

                $result = mysqli_query($connection, $query);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['id']) . "</td>
                                <td>" . htmlspecialchars($row['naam']) . "</td>
                                <td>" . htmlspecialchars($row['categorie_id']) . "</td>
                                <td>" . htmlspecialchars($row['houdbaarheidsdatum']) . "</td>
                                <td>" . (isset($row['aantal']) ? htmlspecialchars($row['aantal']) : 'Niet beschikbaar') . "</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>Geen data gevonden...</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

   
    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('modal').style.display='none'">&times;</span>
            <h2>Product Toevoegen</h2>
            <form method="post" action="toevoegen.php">
                <label for="naam">Naam:</label>
                <input type="text" name="naam" id="naam" required><br><br>

                <label for="beschrijving">Beschrijving:</label>
                <input type="text" name="beschrijving" id="beschrijving" required><br><br>

                <label for="houdbaarheidsdatum">Houdbaarheidsdatum:</label>
                <input type="date" name="houdbaarheidsdatum" id="houdbaarheidsdatum" required><br><br>

                <label for="categorie_id">Categorie ID:</label>
                <input type="number" name="categorie_id" id="categorie_id" required><br><br>

                <label for="aantal">Aantal:</label>
                <input type="number" name="aantal" id="aantal" required><br><br>

                <button type="submit" class="btn btn-success">Opslaan</button>
            </form>
        </div>
    </div>
</body>
</html>
