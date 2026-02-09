<?php
namespace Models;

<<<<<<< HEAD
=======
require_once '/../config/EnvConfig.php';
>>>>>>> 783909157b2129bcffa867e399f3855505c176b2

use Config\EnvConfig;
use PDO;
use PDOException;
use Exception;




class Database
{
    private $pdo;
    private $config;
    
    public function __construct()
    {
        $this->config = EnvConfig::getInstance();
        $this->connect();
    }
    
    private function connect(): void
    {
        $dsn = $this->config->getDatabaseDSN();
        $username = $this->config->get('DB_USER');
        $password = $this->config->get('DB_PASSWORD');
        
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        try {
            $this->pdo = new PDO($dsn, $username, $password, $options);
            
            if ($this->config->isDebug()) {
                error_log("Connexion DB réussie : " . $this->config->get('DB_HOST'));
            }
            
        } catch (PDOException $e) {
            if ($this->config->isDebug()) {
                throw new Exception("Erreur de connexion DB: " . $e->getMessage());
            } else {
                throw new Exception("Erreur de connexion à la base de données");
            }
        }
    }
    
    public function getConnection(): PDO
    {
        return $this->pdo;
    }
    
    // Méthodes utilitaires de base de données...
}