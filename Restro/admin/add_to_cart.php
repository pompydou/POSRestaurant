<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

if (isset($_GET['prod_id']) && isset($_GET['table_id'])) {
    $prod_id = $_GET['prod_id'];
    $table_id = $_GET['table_id'];

    // Récupérer les détails du produit
    $ret = "SELECT * FROM rpos_products WHERE prod_id = ?";
    $stmt = $mysqli->prepare($ret);
    $stmt->bind_param('i', $prod_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $prod = $res->fetch_object();

    // Insérer la commande dans la base de données
    $insertQuery = "INSERT INTO rpos_orders (prod_id, prod_name, prod_price, table_id, customer_name, order_status) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($insertQuery);
    $stmt->bind_param('ississ', $prod_id, $prod->prod_name, $prod->prod_price, $table_id, $_SESSION['user_name'], );
    $stmt->execute();

    // Rediriger vers la page de confirmation
    header("Location: orders.php");
    exit;
} else {
    echo "Erreur lors de l'ajout au panier.";
}
?>