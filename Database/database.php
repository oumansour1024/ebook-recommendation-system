<?php
require_once __DIR__ . '/../Config/EnvConfig.php';

use Config\EnvConfig;

function create_dbApp(){
    try {
        $config = EnvConfig::getInstance();
        $config->load();

        $driver = $config->get('DB_DRIVER', 'mysql');
        $host = $config->get('DB_HOST', 'localhost');
        $port = $config->get('DB_PORT', 3306);
        $user = $config->get('DB_USER', 'root');
        $password = $config->get('DB_PASSWORD', '');
        $name = $config->get('DB_NAME', 'app_database');

        // Connexion sans spécifier la base de données
        $dsn = "{$driver}:host={$host};port={$port}";
        $pdo = new PDO($dsn, 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Création de la base de données si elle n'existe pas
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$name}` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
    
        // ceation user et lui donner les droits
        $pdo->exec("CREATE USER IF NOT EXISTS '{$user}'@'%' IDENTIFIED BY '{$password}'");
        $pdo->exec("GRANT ALL PRIVILEGES ON `{$name}`.* TO '{$user}'@'%'");
        $pdo->exec("FLUSH PRIVILEGES");        
        echo "Base de données '{$name}' créée ou déjà existante.\n";

    } catch (Exception $e) {
        die('Erreur lors de la création de la base de données : ' . $e->getMessage());
    }
}

function insert_dbApp($db) {
    try {
        $stmt = $db->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute(['Admin User', 'admin@gmail.com', password_hash('admin123', PASSWORD_DEFAULT)]);
        echo "Données d'exemple insérées dans la base de données.\n";
    } catch (Exception $e) {
        die('Erreur lors de l\'insertion des données : ' . $e->getMessage());
    }
}




// create_dbApp();