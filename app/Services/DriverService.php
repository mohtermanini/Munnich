<?php

namespace Mohte\DriverLogbook\Services;

use Config\Database;
use Mohte\DriverLogbook\Models\Driver;
use PDO;
use PDOException;

class DriverService
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
     * Fetch driver details by ID.
     * 
     * @param int $id
     * @return Driver|null
     */
    public function getDriverById(int $id): ?Driver
    {
        $query = "SELECT * FROM drivers WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return new Driver(
                $result['id'],
                $result['username'],
                $result['password'],
                $result['created_at']
            );
        }

        return null;
    }
}
