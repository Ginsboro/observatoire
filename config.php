<?php
// Définir les constantes pour la connexion à la base de données
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'kswsjksh_signal');
define('DB_PASSWORD', 'SignaL6248!');
define('DB_NAME', 'kswsjksh_signal');

// Créer une connexion à la base de données
$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Vérification de la connexion
if ($mysqli->connect_error) {
    die("Erreur de connexion à la base de données : " . $mysqli->connect_error);
}
?>
