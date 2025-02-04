<?php
    // Détection de l'environnement (local ou hébergé)
    if ($_SERVER['HTTP_HOST'] == 'localhost') {
        // Configuration pour le serveur local
        $dbuser = "root";
        $dbpass = "";
        $host = "localhost";
        $db = "dbrestauran";
    } else {
        // Configuration pour le serveur en ligne
        $dbuser = "pompydou";
        $dbpass = "T4N5BWp8uGmp46w";
        $host = "mysql-pompydou.alwaysdata.net";
        $db = "pompydou_1234";
    }

    // Connexion à la base de données
    $mysqli = new mysqli($host, $dbuser, $dbpass, $db);

    // Vérification de la connexion
    if ($mysqli->connect_error) {
        die("Échec de la connexion : " . $mysqli->connect_error);
    }
?>
