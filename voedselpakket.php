<!DOCTYPE html>
<html>
<head>
    <title>Voedselpakketten Overzicht</title>
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

    // Handle delete request
    if (isset($_POST['delete_package'])) {
        $package_id = mysqli_real_escape_string($connection, $_POST['package_id']);
        
        mysqli_begin_transaction($connection);
        
        try {
            $restore_query = "UPDATE producten p
                             JOIN voedselpakketten_has_producten vp ON p.id = vp.product_id
                             SET p.aantal = p.aantal + vp.hoeveelheid
                             WHERE vp.pakket_id = '$package_id'";
            mysqli_query($connection, $restore_query);
            
            $delete_query = "DELETE FROM voedselpakketten WHERE id = '$package_id'";
            mysqli_query($connection, $delete_query);
            
            mysqli_commit($connection);
            $success_message = "Voedselpakket succesvol verwijderd!";
        } catch (Exception $e) {
            mysqli_rollback($connection);
            $error_message = "Er is een fout opgetreden bij het verwijderen van het voedselpakket.";
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
            <div class="button">
                <button href="inlog.html" class="background-3"><span class="login">Login</span></button>
            </div>
        </ul>
    </nav>

    <main style="padding: 2rem;">
        <h2>Voedselpakketten Overzicht</h2>

        <?php
        if (isset($success_message)) {
            echo "<div style='padding: 1rem; margin-bottom: 1rem; background: var(--success-background, #d4edda); color: var(--success-text, #155724); border-radius: 4px;'>$success_message</div>";
        } elseif (isset($error_message)) {
            echo "<div style='padding: 1rem; margin-bottom: 1rem; background: var(--error-background, #f8d7da); color: var(--error-text, #721c24); border-radius: 4px;'>$error_message</div>";
        }
        ?>

        <!-- Search form -->
        <form method="get" style="margin-bottom: 2rem;">
            <div style="display: flex; gap: 1rem; align-items: center;">
                <input type="text" 
                       name="search" 
                       placeholder="Zoek op achternaam..." 
                       value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
                       style="flex: 1;">
                <button type="submit">Zoeken</button>
            </div>
        </form>

        <!-- Add new package button -->
        <div class="knop-container" style="margin-bottom: 2rem;">
            <a href="voedselpakket-aanmaken.php" class="toevoegen-knop">
                <span class="icon">+</span> Nieuw Voedselpakket
            </a>
        </div>

        <!-- Packages table -->
        <table>
            <thead>
                <tr>
                    <th>Pakket ID</th>
                    <th>Familie</th>
                    <th>Aanmaakdatum</th>
                    <th>Afleverdatum</th>
                    <th>Aantal Producten</th>
                    <th>Status</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT v.id, v.aanmaakdatum, v.afleverdatum,
                                g.Achternaam, g.Adres,
                                COUNT(vp.product_id) as product_count
                         FROM voedselpakketten v
                         LEFT JOIN gezinnen g ON v.gezin_id = g.id
                         LEFT JOIN voedselpakketten_has_producten vp ON v.id = vp.pakket_id";

                if (isset($_GET['search']) && $_GET['search'] != '') {
                    $filtervalue = mysqli_real_escape_string($connection, $_GET['search']);
                    $query .= " WHERE g.Achternaam LIKE '%$filtervalue%'";
                }

                $query .= " GROUP BY v.id
                           ORDER BY v.afleverdatum DESC";

                $result = mysqli_query($connection, $query);

                if (!$result) {
                    die("Query mislukt: " . mysqli_error($connection));
                }

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $current_date = date('Y-m-d');
                        $status = '';
                        $status_color = '';
                        
                        if ($row['afleverdatum'] < $current_date) {
                            $status = 'Afgeleverd';
                            $status_color = '#198754';  // Success color
                        } elseif ($row['afleverdatum'] == $current_date) {
                            $status = 'Vandaag Afleveren';
                            $status_color = '#fd7e14';  // Warning color
                        } else {
                            $status = 'In Behandeling';
                            $status_color = '#0d6efd';  // Primary color
                        }

                        echo "<tr>
                                <td>" . htmlspecialchars($row['id']) . "</td>
                                <td style='text-align: left;'>" . htmlspecialchars($row['Achternaam']) . " - " . 
                                       htmlspecialchars($row['Adres']) . "</td>
                                <td>" . htmlspecialchars($row['aanmaakdatum']) . "</td>
                                <td>" . htmlspecialchars($row['afleverdatum']) . "</td>
                                <td>" . htmlspecialchars($row['product_count']) . "</td>
                                <td style='color: $status_color;'>" . $status . "</td>
                                <td>
                                    <button onclick='showPackageDetails(" . $row['id'] . ")' 
                                            class='btn btn-info'>Details</button>
                                </td>
                              </tr>";

                        echo "<tr id='details-" . $row['id'] . "' style='display: none;'>
                                <td colspan='7'>
                                    <div style='padding: 1.5rem; background: var(--background-alt);'>";
                        
                        // Fetch and display products in this package
                        $products_query = "SELECT p.naam, vp.hoeveelheid, c.naam as categorie_naam
                                         FROM voedselpakketten_has_producten vp
                                         JOIN producten p ON vp.product_id = p.id
                                         LEFT JOIN categorie c ON p.categorie_id = c.id
                                         WHERE vp.pakket_id = " . $row['id'];
                        
                        $products_result = mysqli_query($connection, $products_query);
                        
                        if (mysqli_num_rows($products_result) > 0) {
                            echo "<h4>Producten in dit pakket:</h4>
                                  <table>
                                    <tr>
                                        <th>Product</th>
                                        <th>Categorie</th>
                                        <th>Aantal</th>
                                    </tr>";
                            
                            while ($product = mysqli_fetch_assoc($products_result)) {
                                echo "<tr>
                                        <td style='text-align: left;'>" . htmlspecialchars($product['naam']) . "</td>
                                        <td>" . htmlspecialchars($product['categorie_naam']) . "</td>
                                        <td>" . htmlspecialchars($product['hoeveelheid']) . "</td>
                                      </tr>";
                            }
                            
                            echo "</table>";
                        }
                        
                        // Action buttons
                        echo "<div style='margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--border); text-align: center;'>
                                <div style='display: flex; justify-content: center; gap: 1rem;'>
                                    <button onclick='showPackageDetails(" . $row['id'] . ")'>
                                        Sluiten
                                    </button>
                                    <form method='POST' style='margin: 0;' onsubmit='return confirmDelete()'>
                                        <input type='hidden' name='package_id' value='" . $row['id'] . "'>
                                        <button type='submit' 
                                                name='delete_package'
                                                style='background-color: var(--delete-button-bg, #ff4b4b); 
                                                       color: var(--delete-button-text, white);'>
                                            Verwijderen
                                        </button>
                                    </form>
                                </div>
                              </div>";
                        
                        echo "    </div>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>Geen voedselpakketten gevonden...</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </main>

    <?php mysqli_close($connection); ?>

    <script>
    function showPackageDetails(packageId) {
        const detailsRow = document.getElementById('details-' + packageId);
        if (detailsRow.style.display === 'none') {
            detailsRow.style.display = 'table-row';
        } else {
            detailsRow.style.display = 'none';
        }
    }

    function confirmDelete() {
        return confirm('Weet je zeker dat je dit voedselpakket wilt verwijderen?');
    }
    </script>
</body>

<style>
    .btn {
        padding: 6px 12px;
        border-radius: 4px;
        cursor: pointer;
        border: none;
        font-size: 14px;
    }

    .btn-info {
        background-color: #17a2b8;
        color: white;
    }

    .btn-info:hover {
        background-color: #138496;
    }
    </style>
</html>