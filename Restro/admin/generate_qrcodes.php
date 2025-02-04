<?php
session_start();
include('../admin/config/config.php'); // Incluez votre fichier de configuration principal
require '../vendor/autoload.php'; // Assurez-vous d'avoir installé Endroid\QrCode via Composer

use Endroid\QrCode\QrCode;

// Vérifier si l'utilisateur est connecté (optionnel)
/*if (!isset($_SESSION['user_id'])) {
    die("Accès non autorisé.");
}*/

// Récupérer toutes les tables depuis la base de données
$tables = [];
$ret = "SELECT * FROM rpos_tables";
$stmt = $mysqli->prepare($ret);
$stmt->execute();
$res = $stmt->get_result();

// Générer un QR Code pour chaque table
while ($table = $res->fetch_object()) {
    // Construire l'URL du menu avec l'ID de la table
    $url = "http://localhost/RestaurantPOS/Restro/client/menu.php?table_id=" . $table->table_id;

    // Créer et enregistrer le QR Code
    $qrCode = new QrCode($url);
    $writer = new \Endroid\QrCode\Writer\PngWriter();
    $writer->write($qrCode)->saveToFile('assets/qrcodes/table_' . $table->table_id . '.png');

    // Ajouter la table au tableau pour affichage (facultatif)
    $tables[] = $table;
}

// Afficher un message de confirmation
echo "<h1>QR Codes générés avec succès !</h1>";
echo "<p>Voici la liste des tables avec leurs QR Codes :</p>";

// Afficher les QR Codes générés (facultatif)
foreach ($tables as $table) {
    echo "<div style='margin: 20px; text-align: center;'>";
    echo "<h3>Table " . htmlspecialchars($table->table_number) . "</h3>";
    echo "<img src='/assets/qrcodes/table_" . htmlspecialchars($table->table_id) . ".png' alt='QR Code Table " . htmlspecialchars($table->table_number) . "'><br>";
    echo "<a href='/assets/qrcodes/table_" . htmlspecialchars($table->table_id) . ".png' download> Télécharger </a>";
    echo "</div>";
}