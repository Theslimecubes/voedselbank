<!DOCTYPE html>
<html>
<head>
    <title>Leveranciers overzicht</title>
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
        $naam = mysqli_real_escape_string($connection, $_POST['naam']);
        $contactpersoon = mysqli_real_escape_string($connection, $_POST['contactpersoon']);
        $telefoon = mysqli_real_escape_string($connection, $_POST['telefoon']);
        $volgende_levering = mysqli_real_escape_string($connection, $_POST['volgende_levering']);

        $query = "INSERT INTO leverancier (naam, contactpersoon, telefoon, volgende_levering) 
                  VALUES ('$naam', '$contactpersoon', '$telefoon', '$volgende_levering')";

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
                    <th>Id</th>
                    <th>Naam</th>
                    <th>Contact</th>
                    <th>Telefoon</th>
                    <th>Volgende levering</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php
                
                $query = "SELECT id, naam, contactpersoon, telefoon, volgende_levering FROM leverancier";

if (isset($_GET['search']) && $_GET['search'] != '') {
    $filtervalue = mysqli_real_escape_string($connection, $_GET['search']);
    $query .= " WHERE CONCAT(naam, contactpersoon, telefoon) LIKE '%$filtervalue%'";
}


$result = mysqli_query($connection, $query);


if (!$result) {
    die("Query mislukt: " . mysqli_error($connection)); 
}

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['naam']) . "</td>";
                echo "<td>" . htmlspecialchars($row['contactpersoon']) . "</td>";
                echo "<td>" . htmlspecialchars($row['telefoon']) . "</td>";
                echo "<td>" . htmlspecialchars($row['volgende_levering']) . "</td>";       
                echo "<td><a <button href='leverancieren_verwijderen.php?id=". htmlspecialchars($row['id']) ."'>Delete</a></td>";
                echo "</tr>";
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
            <h2>Leverancier Toevoegen</h2>
            <form method="POST">
                <label for="naam">Naam:</label>
                <input type="text" name="naam" id="naam" required><br><br>

                <label for="contactpersoon">Contactpersoon:</label>
                <input type="text" name="contactpersoon" id="contactpersoon" required><br><br>

                <label for="telefoon">Telefoon:</label>
                <input type="text" name="telefoon" id="telefoon" required><br><br>

                <label for="volgende_levering">Volgende Levering:</label>
                <input type="date" name="volgende_levering" id="volgende_levering" required><br><br>

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


