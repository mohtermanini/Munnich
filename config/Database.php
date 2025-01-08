<?php

namespace Config;

use PDO;
use PDOException;

/**
 * Database class for managing database connections.
 *
 * This class provides functionality to establish a connection to a database
 * using credentials from an `.env` file.
 */
class Database
{

    private $host;
    private $db_name;
    private $username;
    private $password;
    private $conn = null;

    /**
     * Constructor to load database credentials from the `.env` file.
     *
     * @throws \RuntimeException If the `.env` file fails to load.
     */
    public function __construct()
    {
        $env = parse_ini_file(__DIR__ . '/../.env');

        if (!$env) {
            throw new \RuntimeException('Failed to load .env file.');
        }

        $this->host = $env['DB_HOST'] ?? '';
        $this->db_name = $env['DB_NAME'] ?? '';
        $this->username = $env['DB_USERNAME'] ?? '';
        $this->password = $env['DB_PASSWORD'] ?? '';
    }

    /**
     * Establish a connection to the database.
     *
     * @return PDO|null The PDO instance for the database connection.
     * @throws PDOException If the connection fails.
     */
    public function connect(): ?PDO
    {
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name}",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new PDOException("Database connection failed: " . $e->getMessage(), (int) $e->getCode());
        }

        return $this->conn;
    }
}
