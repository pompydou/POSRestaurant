<!-- client/order.php -->
<?php
session_start();
include('../../config/config.php');

// Vérifier si une table a été sélectionnée
if (!isset($_SESSION['client_table_id'])) {
    die("Table non trouvée.");
}

$table_id = $_SESSION['client_table_id'];

// Récupérer les détails du produit
$prod_id = isset($_GET['prod_id']) ? urldecode($_GET['prod_id']) : '';
$prod_name = isset($_GET['prod_name']) ? urldecode($_GET['prod_name']) : '';
$prod_price = isset($_GET['prod_price']) ? urldecode($_GET['prod_price']) : '';

if (empty($prod_id) || empty($prod_name) || empty($prod_price)) {
    die("Produit invalide.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passer une commande</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <h1>Passer une commande</h1>
    <p>Vous êtes à la Table <?php echo htmlspecialchars($table_id); ?></p>

    <form action="submit_order.php" method="POST">
        <input type="hidden" name="table_id" value="<?php echo htmlspecialchars($table_id); ?>">
        <input type="hidden" name="prod_id" value="<?php echo htmlspecialchars($prod_id); ?>">
        <input type="hidden" name="prod_name" value="<?php echo htmlspecialchars($prod_name); ?>">
        <input type="hidden" name="prod_price" value="<?php echo htmlspecialchars($prod_price); ?>">

        <label for="quantity">Quantité :</label>
        <input type="number" name="quantity" id="quantity" min="1" required>

        <button type="submit">Valider la commande</button>
    </form>
</body>
</html>