<?php
/**
 * Database Configuration and Connection Handler
 * MedFlow - Sistema Administrativo Médico
 */

class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        $config = require __DIR__ . '/config.php';

        try {
            $dsn = sprintf(
                "mysql:host=%s;dbname=%s",
                $config['database']['host'],
                $config['database']['name']
            );

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ];

            $this->connection = new PDO(
                $dsn,
                $config['database']['user'],
                $config['database']['password'],
                $options
            );

        } catch (PDOException $e) {
            error_log("Database Connection Error: " . $e->getMessage());
            die("Error de conexión a la base de datos. Por favor, contacte al administrador.");
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }

    // Prevent cloning
    private function __clone() {}

    // Prevent unserializing
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}
