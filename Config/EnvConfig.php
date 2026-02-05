<?php
namespace Config ;
    /**
     * Classe de gestion des variables d'environnement
     */

class EnvConfig {
    private static $instance = null;
    private $env =[];
    private $required = [
        "APP_NAME",
        "DB_DRIVER",
        "DB_HOST",
        "DB_NAME",
        "DB_USER",
        "DB_PASSWORD",
        "DB_PORT"
    ];
    private function __construct() {

    }

    public static function getInstance():self {
        if (self::$instance === null ){
            self::$instance = new self();
        }
        return self::$instance; 
    }
    /**
     * Charge les variables depuis le fichier .env
     */
    public function load(?string $path = null):void {
        $path =$path ?? dirname(__DIR__).'\.env';
        // Vérifie si le fichier existe 
        if(!file_exists($path)) {
            throw new \Exception('Le fichier .env n\'existe pas : ' . $path);
        }
        // Vérifie les premissions 
        if (!is_readable($path)) {
            throw new \RuntimeException("Le fichier .env n'est pas lisible : {$path}");
        }

        $liens = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($liens as $line) { 
            // Ignore les commentaires
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            // Ignore les lignes sans =
            if (strpos($line, '=') === false) {
                continue;
            }
            // Sépare le nom et la valeur
            list($key, $value) = explode('=', $line, 2);
            
            $key = trim($key);
            $value = trim($value);

            // Gestion des valeurs entourées de guillemets
            $value = $this->parseValue($value);

            // Stockage dans le tableau
            $this->env[$key] = $value;

            // Définition dans les variables globales
            $this->setEnvironmentVariable($key, $value);
        }

        // Validation des variables requises
        $this->validate();
    }

    /**
     * Parse la valeur correctement
     */
    private function parseValue(string $value): string
    {
        // Supprime les guillemets simples ou doubles
        if (preg_match('/^"(.+)"$/s', $value, $matches)) {
            $value = $matches[1];
            $value = str_replace('\\"', '"', $value);
            $value = str_replace('\\n', "\n", $value);
            $value = str_replace('\\r', "\r", $value);
            $value = str_replace('\\t', "\t", $value);
        } elseif (preg_match("/^'(.+)'$/s", $value, $matches)) {
            $value = $matches[1];
            $value = str_replace("\\'", "'", $value);
        }

        // Supprime les espaces autour (sauf à l'intérieur des guillemets)
        $value = trim($value);
        
        // Convertit les booléens
        if (strtolower($value) === 'true') return 'true';
        if (strtolower($value) === 'false') return 'false';
        if (strtolower($value) === 'null') return null;

        // Retourne la valeur
        return $value;
    }

    /**
     * Définit une variable d'environnement
     */
    private function setEnvironmentVariable(string $key, string $value): void
    {
        // Pour PHP
        putenv("{$key}={$value}");
        
        // Pour les superglobales
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }

    /**
     * Valide les variables requises
     */
    private function validate(): void
    {
        foreach ($this->required as $key) {
            if (!isset($this->env[$key]) || empty($this->env[$key])) {
                throw new \RuntimeException(
                    "La variable d'environnement '{$key}' est requise mais non définie."
                );
            }
        }
    }

    /**
     * Récupère une variable d'environnement
     */
    public function get(string $key, $default = null)
    {
        // Priorité : notre tableau -> $_ENV -> putenv -> default
        if (isset($this->env[$key])) {
            return $this->env[$key];
        }

        if (isset($_ENV[$key])) {
            return $_ENV[$key];
        }

        $value = getenv($key);
        if ($value !== false) {
            return $value;
        }

        return $default;
    }

    /**
     * Récupère toutes les variables
     */
    public function getAll(): array
    {
        return $this->env;
    }

    /**
     * Vérifie si l'environnement est de développement
     */
    public function isDevelopment(): bool
    {
        return $this->get('APP_ENV') === 'development';
    }

    /**
     * Vérifie si le debug est activé
     */
    public function isDebug(): bool
    {
        return $this->get('APP_DEBUG') === 'true';
    }

    /**
     * Récupère la configuration DB sous forme de DSN
     */
    public function getDatabaseDSN(): string
    {
        $driver = $this->get('DB_DRIVER', 'mysql');
        $host = $this->get('DB_HOST', 'localhost');
        $port = $this->get('DB_PORT', 3306);
        $name = $this->get('DB_NAME');
        $charset = $this->get('DB_CHARSET', 'utf8mb4');

        if ($port) {
            return "{$driver}:host={$host};port={$port};dbname={$name};charset={$charset}";
        }
        
        return "{$driver}:host={$host};dbname={$name};charset={$charset}";
    }

}

?>