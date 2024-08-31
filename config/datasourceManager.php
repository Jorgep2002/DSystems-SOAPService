<?php
require_once __DIR__ . '/../vendor/autoload.php'; // Autoload dependencies from Composer
 
use Dotenv\Dotenv; // Import the Dotenv class from the Dotenv namespace
 
class DataSourceManager {
    private $conexion;
 
    public function __construct() {
        // Load environment variables from .env file
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();
 
        // Retrieve database credentials from environment variables
        $server = $_ENV['SERVERDB'];
        $user = $_ENV['USERDB'];
        $pass = $_ENV['PASSWORDDB'];
        $db = $_ENV['NAMEDB'];
 
        // Create a new MySQLi connection
        $this->conexion = new mysqli($server, $user, $pass, $db);
 
        // Check for a connection error
        if ($this->conexion->connect_errno) {
            throw new Exception("Conexión Fallida: " . $this->conexion->connect_error);
        }
    }
 
    // Method to get the connection
    public function getConexion() {
        return $this->conexion;
    }
}