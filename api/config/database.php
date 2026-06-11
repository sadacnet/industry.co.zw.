<?php
/**
 * Database Configuration and Connection Class
 * industry.co.zw
 */

// Define site root constant for cross-environment path resolution
// This must be outside the class to ensure it's defined upon inclusion
if (!defined('SITE_ROOT')) {
    // Detect if running on localhost or a live server
    $is_local = (isset($_SERVER['HTTP_HOST']) && ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['REMOTE_ADDR'] === '127.0.0.1'));
    define('SITE_ROOT', $is_local ? '/industry.co.zw' : '');
}

class Database {
    // Database credentials
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $conn;

    public function __construct() {
        // Support for environment variables (Render/Cloud) or default to XAMPP (Local)
        $this->host = getenv('DB_HOST') ?: "localhost";
        $this->db_name = getenv('DB_NAME') ?: "industry_co_zw";
        $this->username = getenv('DB_USER') ?: "root";
        $this->password = getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : "";
    }

    /**
     * Get database connection
     * @return PDO
     */
    public function getConnection() {
        $this->conn = null;

        try {
            // Create PDO connection
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            
            // Set PDO attributes
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            
        } catch(PDOException $exception) {
            // Return error as JSON
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => "Database connection failed: " . $exception->getMessage()
            ]);
            exit;
        }

        return $this->conn;
    }
}
?>