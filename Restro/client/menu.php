<!-- client/menu.php -->
<?php
session_start();
include('../admin/config/config.php');

// Vérifier si un ID de table est fourni
if (!isset($_GET['table_id'])) {
    die("Table non trouvée.");
}

$table_id = $_GET['table_id'];
$_SESSION['client_table_id'] = $table_id; // Stocker l'ID de la table dans la session

// Récupérer les produits depuis la base de données
$ret = "SELECT * FROM rpos_products WHERE prod_status = 'available'";
$stmt = $mysqli->prepare($ret);
$stmt->execute();
$res = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Numérique</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <h1>Menu Numérique</h1>
    <p>Vous êtes à la Table <?php echo htmlspecialchars($table_id); ?></p>

    <div class="menu">
        <?php while ($prod = $res->fetch_object()) { ?>
            <div class="menu-item">
                <img src="assets/img/products/<?php echo $prod->prod_img; ?>" alt="<?php echo $prod->prod_name; ?>">
                <h3><?php echo htmlspecialchars($prod->prod_name); ?></h3>
                <p><?php echo htmlspecialchars($prod->prod_description); ?></p>
                <p><strong>Prix : $<?php echo htmlspecialchars($prod->prod_price); ?></strong></p>
                <a href="order.php?prod_id=<?php echo urlencode($prod->prod_id); ?>&prod_name=<?php echo urlencode($prod->prod_name); ?>&prod_price=<?php echo urlencode($prod->prod_price); ?>">Ajouter au panier</a>
            </div>
        <?php } ?>
    </div>
</body>
</html>