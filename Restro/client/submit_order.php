<!-- client/submit_order.php -->
<?php
session_start();
include('../../config/config.php');

// Vérifier si une table a été sélectionnée
if (!isset($_SESSION['client_table_id'])) {
    die("Table non trouvée.");
}

$table_id = $_SESSION['client_table_id'];

// Récupérer les détails de la commande
$prod_id = isset($_POST['prod_id']) ? htmlspecialchars($_POST['prod_id']) : '';
$prod_name = isset($_POST['prod_name']) ? htmlspecialchars($_POST['prod_name']) : '';
$prod_price = isset($_POST['prod_price']) ? htmlspecialchars($_POST['prod_price']) : '';
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;

if (empty($prod_id) || empty($prod_name) || empty($prod_price) || $quantity <= 0) {
    die("Commande invalide.");
}

// Insérer la commande dans la base de données
$order_code = uniqid(); // Générer un code de commande unique
$query = "INSERT INTO rpos_orders (prod_qty, order_code, customer_name, prod_id, prod_name, prod_price, table_id, order_status) 
          VALUES (?, ?, 'Client QR', ?, ?, ?, ?, 'En attente')";
$stmt = $mysqli->prepare($query);
$stmt->bind_param('issdssi', $quantity, $order_code, $prod_id, $prod_name, $prod_price, $table_id);
$stmt->execute();

if ($stmt) {
    // Mettre à jour le statut de la table en "occupée"
    $updateTableStatus = "UPDATE rpos_tables SET status = 1 WHERE table_id = ?";
    $updateStmt = $mysqli->prepare($updateTableStatus);
    $updateStmt->bind_param('i', $table_id);
    $updateStmt->execute();

    echo "Commande passée avec succès.";
} else {
    echo "Une erreur est survenue. Veuillez réessayer.";
}
?>