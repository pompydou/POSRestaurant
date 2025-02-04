<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();

if (isset($_GET['table_id'])) {
    $table_id = $_GET['table_id'];

    // Mettre à jour le statut de la table en "occupée"
    $updateQuery = "UPDATE rpos_tables SET status = 1 WHERE table_id = ?";
    $stmt = $mysqli->prepare($updateQuery);
    $stmt->bind_param('i', $table_id);
    $stmt->execute();

    // Enregistrer l'ID de la table dans la session
    $_SESSION['selected_table_id'] = $table_id;

    // Rediriger vers la page de commande
    header("Location: make_oder.php");
    exit;
} else {
    echo "Table non trouvée.";
}
?>