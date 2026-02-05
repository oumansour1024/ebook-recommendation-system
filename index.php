<?php

require_once __DIR__ . '/config/EnvConfig.php';

use Config\EnvConfig;

// Initialisation de la configuration
try {
    $config = EnvConfig::getInstance();
    $config->load();
    
    // Configuration de base de PHP selon .env
    if ($config->isDebug()) {
        error_reporting(E_ALL);
        ini_set('display_errors', '1');
    } else {
        error_reporting(0);
        ini_set('display_errors', '0');
    }
    
    date_default_timezone_set($config->get('APP_TIMEZONE', 'UTC'));
    
    
} catch (Exception $e) {
    die('Erreur de configuration : ' . $e->getMessage());
}

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Description du projet">
    
    <title><?php echo htmlspecialchars($config->get('APP_NAME')); ?></title>
    
    <!-- Favicon -->
    <link rel="icon" href="public/assets/favicon.ico" type="image/x-icon">
    
    <!-- CSS -->
    <link rel="stylesheet" href="css/styles.css">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- JavaScript -->
    <script src="/public/js/main.js" defer></script>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($config->get('APP_NAME')); ?></title>
    
 <link rel="stylesheet" href="/public/css/styles.css">

</head>
<body>


  
</body>
</html>