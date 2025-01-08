<?php

namespace Mohte\DriverLogbook\Services;

use Config\Database;
use PDO;
use PDOException;

class VehicleService
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
     * Fetch vehicle usage data (number of trips) with optional date range filters.
     *
     * @param string|null $fromDate
     * @param string|null $toDate
     * @return array
     */
    public function getVehicleUsage(?string $fromDate, ?string $toDate): array
    {
        $query = "
            SELECT 
                vehicles.license_plate AS vehicle_name,
                COUNT(trips.id) AS trip_count
            FROM vehicles
            LEFT JOIN trips ON vehicles.id = trips.vehicle_id
            WHERE (:fromDate IS NULL OR trips.departure_time >= :fromDate)
              AND (:toDate IS NULL OR trips.departure_time <= :toDate)
            GROUP BY vehicles.license_plate
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':fromDate', $fromDate);
        $stmt->bindParam(':toDate', $toDate);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Create a new vehicle.
     *
     * @param string $licensePlate
     * @param int $driverId
     * @return int|null
     */
    public function createVehicle(string $licensePlate, int $driverId): ?int
    {
        $query = "
            INSERT INTO vehicles (license_plate, driver_id)
            VALUES (:licensePlate, :driverId)
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':licensePlate', $licensePlate, PDO::PARAM_STR);
        $stmt->bindValue(':driverId', $driverId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return (int) $this->db->lastInsertId();
        }

        return null;
    }

    /**
     * Get a list of vehicles.
     *
     * @return array
     */
    public function getVehicles(): array
    {
        $query = "SELECT license_plate FROM vehicles ORDER BY license_plate ASC";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Fetch vehicle details by license plate.
     *
     * @param string $licensePlate
     * @return array|null
     */
    public function getVehicleByLicensePlate(string $licensePlate): ?array
    {
        $query = "
            SELECT * 
            FROM vehicles
            WHERE license_plate = :licensePlate
            LIMIT 1
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':licensePlate', $licensePlate, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Fetch vehicle details by ID.
     *
     * @param int $vehicleId
     * @return array|null
     */
    public function getVehicleById(int $vehicleId): ?array
    {
        $query = "SELECT * FROM vehicles WHERE id = :vehicleId";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':vehicleId', $vehicleId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}
