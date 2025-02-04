<?php
session_start();
include('../admin/config/config.php'); // Inclure le fichier de configuration principal

// Vérifier si un ID de table est fourni
if (!isset($_GET['table_id'])) {
    die("ID de table manquant.");
}

$table_id = htmlspecialchars($_GET['table_id']);

// Vérifier si le fichier QR Code existe
$qrCodePath = './assets/qrcodes/table_' . $table_id . '.png';
if (!file_exists($qrCodePath)) {
    die("QR Code introuvable pour cette table.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impression QR Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .qrcode-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr); /* 3 QR codes par ligne */
            gap: 30px; /* Espacement entre les QR codes */
            text-align: center;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .qrcode-item {
            padding: 10px;
            background: #fff;
            border-radius: 10px;
        }
        .qrcode-item img {
            width: 200px; /* Augmentation de la taille */
            height: 200px;
            object-fit: contain;
        }
        .qrcode-item h4 {
            margin-top: 10px;
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="qrcode-container">
        <?php for ($i = 1; $i <= 9; $i++) { ?> <!-- 9 QR codes en tout -->
            <div class="qrcode-item">
                <h4>Table <?php echo htmlspecialchars($table_id); ?></h4>
                <img src="<?php echo $qrCodePath; ?>" alt="QR Code Table <?php echo htmlspecialchars($table_id); ?>">
            </div>
        <?php } ?>
    </div>

    <script>
        window.onload = function() {
            window.print();
            window.onafterprint = function() {
                window.close();
            };
        };
    </script>
</body>
</html>
