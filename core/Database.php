<?php

namespace App\Core;

use PDO;
use PDOException;

/**
 * Singleton Database Wrapper using PDO
 */
class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";charset=utf8mb4";
            
            // Note: We don't specify DB_NAME here initially in case the database doesn't exist yet, 
            // but we can try to connect to it or create it in install.php.
            // Under normal circumstances, we connect to the database.
            // We can detect if DB_NAME is set and connect to it.
            if (defined('DB_NAME') && DB_NAME !== '') {
                $dsn .= ";dbname=" . DB_NAME;
            }

            $this->connection = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            die("Database Connection Failed: " . $e->getMessage());
        }
    }

    // Get connection instance (Singleton)
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Get PDO connection object
    public function getConnection() {
        return $this->connection;
    }

    // Helper for executing prepared statements easily
    public function query($sql, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            die("Query Execution Error: " . $e->getMessage() . " (Query: " . $sql . ")");
        }
    }

    // Single row fetch helper
    public function fetch($sql, $params = []) {
        return $this->query($sql, $params)->fetch();
    }

    // Multiple rows fetch helper
    public function fetchAll($sql, $params = []) {
        return $this->query($sql, $params)->fetchAll();
    }

    // Insert and return last inserted ID
    public function lastInsertId() {
        return $this->connection->lastInsertId();
    }
}
