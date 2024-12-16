<!DOCTYPE html>
<html>
<head>
    <title>Gebruikers overzicht</title>
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
        $gebruikersnaam = mysqli_real_escape_string($connection, $_POST['gebruikersnaam']);
        $email = mysqli_real_escape_string($connection, $_POST['email']);
        $wachtwoord = mysqli_real_escape_string($connection, $_POST['wachtwoord']);
        $functie = mysqli_real_escape_string($connection, $_POST['functie']);
        

        $query = "INSERT INTO gebruikers (gebruikersnaam, email, wachtwoord, functie) 
                  VALUES ('$gebruikersnaam', '$email', '$wachtwoord', '$functie')";

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
                    <a href="gebruikers">Gebruikers</a>
                    <a href="gezinnen">Gezinnen</a>
                    <a href="leverancieren.php">Leveranciers</a>
                    <a href="voedselpakket.php">Voedsel pakketten</a>
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
                    <th>Naam</th>
                    <th>Email</th>
                    <th>Wachtwoord</th>
                    <th>Functie</th>
                    <th>Delete</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php
                
                $query = "SELECT gebruikersnaam, email, wachtwoord, functie FROM gebruikers";

if (isset($_GET['search']) && $_GET['search'] != '') {
    $filtervalue = mysqli_real_escape_string($connection, $_GET['search']);
    $query .= " WHERE CONCAT(gebruikersnaam, email, wachtwoord, functie) LIKE '%$filtervalue%'";
}


$result = mysqli_query($connection, $query);


if (!$result) {
    die("Query mislukt: " . mysqli_error($connection)); 
}

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                
                <td>" . htmlspecialchars($row['gebruikersnaam']) . "</td>
                <td>" . htmlspecialchars($row['email']) . "</td>
                <td>" . htmlspecialchars($row['wachtwoord']) . "</td>
                <td>" . htmlspecialchars($row['functie']) . "</td>"
                ?>
                <td>"<a href="gebruikerverwijderen.php?delete=<?php echo $data['id']; ?>" class="button">Delete</a>"</td>
                <?php
                "</tr>";
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
            <h2>Gebruiker toevoegen</h2>
            <form method="POST">
                <label for="gebruikersnaam">Gebruikersnaam:</label>
                <input type="text" name="gebruikersnaam" id="gebruikersnaam" required><br><br>

                <label for="email">Email:</label>
                <input type="text" name="email" id="email" required><br><br>

                <label for="wachtwoord">Wachtwoord:</label>
                <input type="text" name="wachtwoord" id="wachtwoord" required><br><br>

                <label for="functie">Functie:</label>
                <select name="functie" id="functie" required>
                    <option value="">-- Selecteer een functie --</option>
                    <?php
                    
                    $functie_query = "SELECT id, functie FROM Functie";
                    $functie_result = mysqli_query($connection, $functie_query);

                    while ($functie = mysqli_fetch_assoc($functie_result)) {
                        echo '<option value="' . htmlspecialchars($functie['id']) . '">' . htmlspecialchars($functie['functie']) . '</option>';
                    }
                    ?>
                    </select><br><br>
                <input type="submit" value="Opslaan">
            </form>
            <?php
            
            if (isset($success_message)) {
                echo "<p style='color: green;'>$success_message</p>";
            } elseif (isset($error_message)) {
                echo "<p style='color: red;'>$error_message</p>";
            }
            ?>
        </div>
    </div>

    <?php mysqli_close($connection); ?>
</body>
</html
