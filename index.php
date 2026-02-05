<?php
spl_autoload_register(function ($class) {
    $parts = explode('\\', $class);
    $className = array_pop($parts);
    $namespace = implode('\\', $parts);
    
    $namespacePath = str_replace('\\', '/', $namespace);
    
    $paths = [
        __DIR__ . '/' . $namespacePath . '/' . $className . '.php',
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

use Config\EnvConfig;
use Controllers\UserController;
use Models\Database;


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
    
    date_default_timezone_set($config->get('APP_TIMEZONE', 'UTC')) ;

    // Connexion à la base de données
    $db = new Database();

    $usercontroller = new UserController();




    
    
} catch (Exception $e) {
    die('Erreur de configuration : ' . $e->getMessage());
}

try {
    // Initialisation de la session
    session_start();

    // Détermination de la vue à afficher
    $view = $_GET['page'] ?? 'home';
    $messages = [
        'message' => $_GET['message'] ?? null,
        'type' => $_GET['type'] ?? null
    ];




} catch (Exception $e) {
    
}

?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Description du projet">

    <title>E-Book | <?php echo htmlspecialchars(ucwords(string:isset($_GET['page'])?$_GET['page']:'Accueil')); ?></title>

    <!-- Favicon -->
    <link rel="icon" href="/app/public/assets/favicon.ico" type="image/x-icon">

    <!-- CSS -->
    <link rel="stylesheet" href="/app/public/css/styles.css">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <script>
    <?php include __DIR__ . '/Views/components/message.php'; ?>
    </script>
    <!-- icon link -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
   

</head>

<body>
   
    <?php include __DIR__ . '/Views/layouts/header.php'; ?>
    <div class="main-content">
        <div id="toast-container"></div>
        <?php 

            switch ($view) {
                case 'home':
                    include __DIR__ . '/Views/home/index.php';
                    break;
                case 'login':
                    $usercontroller->login($db->getConnection());
                    break;
                case 'register':
                    $usercontroller->register($db->getConnection());
                    break;
                case 'logout':
                    $usercontroller->logout();
                    break;
                case 'dashboard':
                    include __DIR__ . '/Views/dashboard.php';
                    break;
                default:
                    include __DIR__ . '/Views/error/404.php';
                    break;
            }
        ?>
    </div>
    <?php include __DIR__ . '/Views/layouts/footer.php'; ?>
    <?php include __DIR__ . '/Views/components/message.php'; ?>

    <!-- JavaScript -->
    <script src="/app/public/js/main.js"></script>
    <script>
        <?php if ($messages['message']): ?>
            showToast("<?php echo addslashes($messages['message']); ?>", "<?php echo addslashes($messages['type']); ?>");
        <?php endif; ?>
    </script>

</body>


</html>