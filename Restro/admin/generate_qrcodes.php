<?php
session_start();
include('../admin/config/config.php'); // Inclure le fichier de configuration principal
require '../vendor/autoload.php'; // Charger Endroid QR Code

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

// Vérifier si l'utilisateur est connecté et est administrateur
/*if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    die("Accès non autorisé.");
}*/

$dir = __DIR__ . "./assets/qrcodes/";
if (!is_dir($dir)) {
    mkdir($dir, 0777, true); // Crée le dossier avec les bonnes permissions
}


// Récupérer les tables depuis la base de données
$tables = [];
$ret = "SELECT * FROM rpos_tables";
$stmt = $mysqli->prepare($ret);
$stmt->execute();
$res = $stmt->get_result();

// Générer les QR Codes pour chaque table
// Définir l'URL de base en fonction de l'environnement
if ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1') {
  // Environnement local
  $base_url = "http://localhost/RestaurantPOS/Restro/client/";
} else {
  // Environnement de production (hébergé)
  $base_url = "https://pos-resto-3xp2n.kinsta.app/Restro/client/";
}

// Générer les QR Codes pour chaque table
while ($table = $res->fetch_object()) {
  $url = $base_url . "menu.php?table_id=" . $table->table_id;
  $qrCode = new QrCode($url);
  $writer = new PngWriter();
  $qrCodePath = './assets/qrcodes/table_' . $table->table_id . '.png'; // Correction du chemin
  $writer->write($qrCode)->saveToFile($qrCodePath);
  $tables[] = $table;
}
// Inclure les fichiers partiels
require_once('../admin/partials/_head.php');
?>

<body>
    <!-- Sidenav -->
    <?php require_once('../admin/partials/_sidebar.php'); ?>

    <!-- Main content -->
    <div class="main-content">
        <!-- Top navbar -->
        <?php require_once('../admin/partials/_topnav.php'); ?>

    <!-- Header -->
    <div style="background-image: url(assets/img/theme/restro00.jpg); background-size: cover;" class="header pb-8 pt-5 pt-md-8">
      <span class="mask bg-gradient-dark opacity-8"></span>
      <div class="container-fluid">
        <div class="header-body">
          <h2 class="text-white">Génération des QR Codes</h2>
        </div>
      </div>
    </div>

    <!-- Page content -->
    <div class="container-fluid mt--8">
      <div class="row">
        <div class="col">
          <div class="card shadow">
            <div class="card-header border-0">
              <h3>QR Codes générés avec succès !</h3>
            </div>
            <div class="card-body">
              <p>Voici la liste des tables avec leurs QR Codes :</p>
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <?php foreach ($tables as $table) { ?>
                                    <div class="col-lg-3 col-md-4 col-sm-6 text-center mb-4">
                                        <h4>Table <?php echo htmlspecialchars($table->table_number); ?></h4>
                                        <img src="./assets/qrcodes/table_<?php echo htmlspecialchars($table->table_id); ?>.png"
                                             alt="QR Code Table <?php echo htmlspecialchars($table->table_number); ?>"
                                             class="img-thumbnail" style="max-width: 150px;">
                                             <br> <!-- Ajout d'un saut de ligne pour espacer l'image et le bouton -->
                                                <button class="btn btn-sm btn-primary mt-2"  onclick="printQRCode('<?php echo htmlspecialchars($table->table_id); ?>')"> <!-- Ajouter un événement onclick pour imprimer le code QR -->
                                                        <i class="fas fa-print"></i>
                                                        Print qrcode
                                                </button>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <?php require_once('../admin/partials/_footer.php'); ?>
    </div>

    <!-- Scripts -->
    <?php require_once('../admin/partials/_scripts.php'); ?>

    <script>
        function printQRCode(tableId) {
            window.open('print_qrcode.php?table_id=' + tableId, '_blank');
        }
    </script>
</body>
</html>
