<?php
    // Détection de l'environnement (local ou hébergé)
    if ($_SERVER['HTTP_HOST'] == 'localhost') {
        // Configuration locale
        $DB_host = "localhost";
        $DB_user = "root";
        $DB_pass = "";
        $DB_name = "dbrestauran";
    } else {
        // Configuration en ligne
        $DB_host = "mysql-pompydou.alwaysdata.net";
        $DB_user = "pompydou";
        $DB_pass = "T4N5BWp8uGmp46w";
        $DB_name = "pompydou_1234";
    }

    try {
        $DB_con = new PDO("mysql:host={$DB_host};dbname={$DB_name}", $DB_user, $DB_pass);
        $DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
?>
