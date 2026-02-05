<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($config->get('APP_NAME')); ?></title>
    <link rel="icon" href="assets/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="/styles.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .container { max-width: 1200px; margin: 0 auto; }
        .success { color: green; padding: 10px; background: #e8f5e8; }
        .error { color: red; padding: 10px; background: #ffe8e8; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .env-value { font-family: monospace; word-break: break-all; }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($config->get('APP_NAME')); ?></h1>
        
        <?php if ($config->isDevelopment()): ?>
            <div class="success">
                Mode développement activé
            </div>
            
            <h2>Variables d'environnement</h2>
            <table>
                <thead>
                    <tr>
                        <th>Variable</th>
                        <th>Valeur</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $envVars = [
                        'APP_NAME' => 'Nom de l\'application',
                        'APP_ENV' => 'Environnement',
                        'APP_DEBUG' => 'Mode débogage',
                        'DB_HOST' => 'Hôte de la base de données',
                        'DB_NAME' => 'Nom de la base',
                        'API_BASE_URL' => 'URL de l\'API',
                        'CACHE_DRIVER' => 'Driver de cache',
                    ];
                    
                    foreach ($envVars as $var => $desc):
                    ?>
                    <tr>
                        <td><strong><?php echo $var; ?></strong></td>
                        <td class="env-value"><?php echo htmlspecialchars($config->get($var, 'Non défini')); ?></td>
                        <td><?php echo $desc; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <h3>DSN de connexion à la base de données</h3>
            <p><code><?php echo htmlspecialchars($config->getDatabaseDSN()); ?></code></p>
            
        <?php else: ?>
            <div class="error">
                Mode production - Les détails sont masqués pour la sécurité
            </div>
            <p>Application fonctionnelle avec les paramètres de production.</p>
        <?php endif; ?>
    </div>
</body>
</html>