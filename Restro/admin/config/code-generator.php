<?php
// Fonction pour générer une chaîne aléatoire
function generate_random_string($length, $characters) {
    if ($length > strlen($characters)) {
        throw new Exception("La longueur demandée est supérieure à la taille de la chaîne source.");
    }
    return substr(str_shuffle($characters), 0, $length);
}

// Fonction pour générer un ID hexadécimal
function generate_hex_id($bytes) {
    return bin2hex(random_bytes($bytes));
}

// Jeton de réinitialisation de mot de passe (30 caractères)
$tk = generate_random_string(30, "QWERTYUIOPLKJHGFDSAZXCVBNM1234567890");

// Mot de passe aléatoire (10 caractères)
$rc = generate_random_string(10, "QWERTYUIOPLKJHGFDSAZXCVBNM1234567890");

// Code système alpha (4 lettres majuscules)
$alpha = generate_random_string(4, "QWERTYUIOPLKJHGFDSAZXCVBNM");

// Code système beta (4 chiffres)
$beta = generate_random_string(4, "1234567890");

// Identifiant de checksum (12 octets hexadécimaux)
$checksum = generate_hex_id(12);

// Identifiant d'opération (4 octets hexadécimaux)
$operation_id = generate_hex_id(4);

// Identifiant client (6 octets hexadécimaux)
$cus_id = generate_hex_id(6);

// Identifiant produit (5 octets hexadécimaux)
$prod_id = generate_hex_id(5);

// Identifiant de commande (5 octets hexadécimaux)
$orderid = generate_hex_id(5);

// Identifiant de paiement (3 octets hexadécimaux)
$payid = generate_hex_id(3);

// Code M-PESA (10 caractères alphanumériques)
$mpesaCode = generate_random_string(10, "Q1W2E3R4T5Y6U7I8O9PLKJHGFDSAZXCVBNM");
?>