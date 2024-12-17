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

    // Handle form submission directly in this page
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $postcode = mysqli_real_escape_string($connection, $_POST['postcode']);
        $achternaam = mysqli_real_escape_string($connection, $_POST['achternaam']);
        $adres = mysqli_real_escape_string($connection, $_POST['adres']);
        $volwassenen = intval($_POST['volwassenen']);
        $kinderen = intval($_POST['kinderen']);
        $babies = intval($_POST['babies']);
        $allergenen_id = !empty($_POST['allergenen']) ? intval($_POST['allergenen']) : "NULL";

        $query = "INSERT INTO gezinnen (postcode, achternaam, adres, volwassenen, kinderen, babies, allergenen_id) 
                  VALUES ('$postcode', '$achternaam', '$adres', $volwassenen, $kinderen, $babies, $allergenen_id)";
        
        if (mysqli_query($connection, $query)) {
            $success_message = "Familie succesvol toegevoegd!";
        } else {
            $error_message = "Error: " . mysqli_error($connection);
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
                    <a href="leverancieren.php">Leveranciers</a>
                    <a href="voedselpakket.php">Voedsel pakketten</a>
                </div>
            </div>
            <li><a href="contact.html">Contact</a></li>
            <li><a href="inlog2.php" class="background-3">Logout</span></a></li>
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
                $query = "SELECT g.*, a.naam as allergeen_naam 
                         FROM gezinnen g 
                         LEFT JOIN allergenen a ON g.allergenen_id = a.id";

                if (isset($_GET['search']) && $_GET['search'] != '') {
                    $filtervalue = mysqli_real_escape_string($connection, $_GET['search']);
                    $query .= " WHERE CONCAT(g.postcode, g.achternaam, g.adres) LIKE '%$filtervalue%'";
                }

                $result = mysqli_query($connection, $query);

                if (!$result) {
                    die("Query mislukt: " . mysqli_error($connection));
                }

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['Postcode']) . "</td>
                                <td>" . htmlspecialchars($row['Achternaam']) . "</td>
                                <td>" . htmlspecialchars($row['Adres']) . "</td>
                                <td>" . htmlspecialchars($row['Volwassenen']) . "</td>
                                <td>" . htmlspecialchars($row['Kinderen']) . "</td>
                                <td>" . htmlspecialchars($row['Babies']) . "</td>
                                <td>" . ($row['allergeen_naam'] ? htmlspecialchars($row['allergeen_naam']) : 'Geen') . "</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>Geen data gevonden...</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('modal').style.display='none'">&times;</span>
            <h2>Gezin Toevoegen</h2>
            <?php
            if (isset($success_message)) {
                echo "<p style='color: green;'>$success_message</p>";
            } elseif (isset($error_message)) {
                echo "<p style='color: red;'>$error_message</p>";
            }
            ?>
            <form method="POST">
                <label for="postcode">Postcode:</label>
                <input type="text" name="postcode" id="postcode" required pattern="[1-9][0-9]{3}\s?[A-Za-z]{2}" title="Vul een geldige postcode in (bijv. 1234 AB)"><br><br>

                <label for="achternaam">Achternaam:</label>
                <input type="text" name="achternaam" id="achternaam" required><br><br>

                <label for="adres">Adres:</label>
                <input type="text" name="adres" id="adres" required><br><br>

                <label for="volwassenen">Volwassenen:</label>
                <input type="number" name="volwassenen" id="volwassenen" required min="0"><br><br>

                <label for="kinderen">Kinderen:</label>
                <input type="number" name="kinderen" id="kinderen" required min="0"><br><br>

                <label for="babies">Babies:</label>
                <input type="number" name="babies" id="babies" required min="0"><br><br>

                <label for="allergenen">Allergenen:</label>
                <select name="allergenen" id="allergenen">
                    <option value="">Geen allergenen</option>
                    <?php
                    $allergen_query = "SELECT id, naam FROM allergenen";
                    $allergen_result = mysqli_query($connection, $allergen_query);
                    while ($allergen = mysqli_fetch_assoc($allergen_result)) {
                        echo "<option value='" . $allergen['id'] . "'>" . htmlspecialchars($allergen['naam']) . "</option>";
                    }
                    ?>
                </select><br><br>

                <input type="submit" value="Opslaan" class="btn btn-success">
            </form>
        </div>
    </div>

    <?php mysqli_close($connection); ?>

    <script>
    // Close modal when clicking outside of it
    window.onclick = function(event) {
        var modal = document.getElementById('modal');
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // Clear form and messages when modal is closed
    document.querySelector('.close').onclick = function() {
        document.querySelector('form').reset();
        var messages = document.querySelectorAll('.modal-content > p');
        messages.forEach(function(message) {
            message.remove();
        });
    }
    </script>
</body>
</html>