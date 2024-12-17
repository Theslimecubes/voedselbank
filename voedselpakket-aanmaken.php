<!DOCTYPE html>
<html>
<head>
    <title>Voedselpakket aanmaken</title>
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

    // Handle form submission for creating a food package
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_package'])) {
        $gezin_id = mysqli_real_escape_string($connection, $_POST['gezin_id']);
        $aanmaakdatum = date('Y-m-d');
        $afleverdatum = mysqli_real_escape_string($connection, $_POST['afleverdatum']);
    
        // Simple insert using gezin_id directly
        $query = "INSERT INTO voedselpakketten (gezin_id, aanmaakdatum, afleverdatum) 
                  VALUES ('$gezin_id', '$aanmaakdatum', '$afleverdatum')";
        
        if (mysqli_query($connection, $query)) {
            $pakket_id = mysqli_insert_id($connection);
            $success = true;
            
            // Process selected products
            if (isset($_POST['products'])) {
                foreach ($_POST['products'] as $product_id => $quantity) {
                    if ($quantity > 0) {
                        $product_id = mysqli_real_escape_string($connection, $product_id);
                        $quantity = mysqli_real_escape_string($connection, $quantity);
                        
                        // Insert product into package
                        $query = "INSERT INTO voedselpakketten_has_producten (pakket_id, product_id, hoeveelheid) 
                                 VALUES ('$pakket_id', '$product_id', '$quantity')";
                        if (!mysqli_query($connection, $query)) {
                            $success = false;
                        }
                        
                        // Update product inventory
                        $update_query = "UPDATE producten 
                                       SET aantal = aantal - $quantity 
                                       WHERE id = '$product_id' AND aantal >= $quantity";
                        mysqli_query($connection, $update_query);
                    }
                }
            }
            
            if ($success) {
                $success_message = "Voedselpakket succesvol aangemaakt!";
            } else {
                $error_message = "Er is een fout opgetreden bij het toevoegen van producten.";
            }
        } else {
            $error_message = "Er is een fout opgetreden bij het aanmaken van het voedselpakket.";
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
            <li><a href="contact">Contact</a></li>
            <div class="button">
                <button href="inlog.html" class="background-3"><span class="login">Login</span></button>
            </div>
        </ul>
    </nav>

    <div class="container mt-5">
        <h2>Nieuw Voedselpakket Aanmaken</h2>
        
        <?php
        if (isset($success_message)) {
            echo "<p style='color: green;'>$success_message</p>";
        } elseif (isset($error_message)) {
            echo "<p style='color: red;'>$error_message</p>";
        }
        ?>

        <form method="POST">
            <div class="form-group">
                <label for="gezin_id">Selecteer Gezin:</label>
                <select name="gezin_id" id="gezin_id" required>
                    <option value="">-- Selecteer een gezin --</option>
                    <?php
                    $query = "SELECT id, Achternaam, Adres FROM gezinnen ORDER BY Achternaam";
                    $result = mysqli_query($connection, $query);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='" . htmlspecialchars($row['id']) . "'>" . 
                             htmlspecialchars($row['Achternaam']) . " - " . 
                             htmlspecialchars($row['Adres']) . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="afleverdatum">Aflever Datum:</label>
                <input type="date" name="afleverdatum" id="afleverdatum" required 
                       min="<?php echo date('Y-m-d'); ?>">
            </div>

            <h3>Beschikbare Producten:</h3>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Categorie</th>
                        <th>Beschikbaar Aantal</th>
                        <th>Houdbaarheidsdatum</th>
                        <th>Hoeveelheid voor Pakket</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT p.id, p.naam, p.aantal, p.houdbaarheidsdatum, c.naam as categorie_naam 
                             FROM producten p 
                             LEFT JOIN categorie c ON p.categorie_id = c.id 
                             WHERE p.aantal > 0 AND p.houdbaarheidsdatum >= CURRENT_DATE()
                             ORDER BY c.naam, p.naam";
                    
                    $result = mysqli_query($connection, $query);
                    
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['naam']) . "</td>
                                <td>" . htmlspecialchars($row['categorie_naam']) . "</td>
                                <td>" . htmlspecialchars($row['aantal']) . "</td>
                                <td>" . htmlspecialchars($row['houdbaarheidsdatum']) . "</td>
                                <td>
                                    <input type='number' name='products[" . $row['id'] . "]' 
                                           min='0' max='" . $row['aantal'] . "' value='0'>
                                </td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>

            <input type="submit" name="create_package" value="Voedselpakket Aanmaken" class="btn btn-primary">
        </form>
    </div>

    <?php mysqli_close($connection); ?>

    <script>
    // Add basic form validation
    document.querySelector('form').onsubmit = function(e) {
        const products = document.querySelectorAll('input[type="number"]');
        let hasProducts = false;
        
        products.forEach(function(product) {
            if (parseInt(product.value) > 0) {
                hasProducts = true;
            }
        });
        
        if (!hasProducts) {
            alert('Selecteer ten minste één product voor het voedselpakket.');
            e.preventDefault();
            return false;
        }
        
        return true;
    };
    </script>
</body>
</html>