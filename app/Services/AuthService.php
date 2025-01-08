<?php

namespace Mohte\DriverLogbook\Services;

use Config\Database;
use PDO;
use Exception;
use PDOException;

class AuthService
{
    private PDO $db;

    public function __construct()
    {
        try {
            $database = new Database();
            $this->db = $database->connect();
        } catch (PDOException $e) {
            throw new PDOException("Failed to establish a database connection: " . $e->getMessage(), (int) $e->getCode());
        }
    }

    /**
     * Authenticate a user by username and password.
     *
     * @param string $username
     * @param string $password
     * @return array|null Returns user data on success, or null if authentication fails.
     * @throws Exception If the query or password verification fails.
     */
    public function authenticate(string $username, string $password): ?array
    {
        $query = "SELECT * FROM drivers WHERE username = :username LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':username', $username);
        $stmt->execute();

        $driver = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($driver && password_verify($password, $driver['password'])) {
            return $driver;
        }

        return null;
    }
}
