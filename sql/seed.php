<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Config\Database;

/**
 * Seed class for populating database tables with test data.
 *
 */
class Seed
{
    private $db;

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
     * Run the seeder to populate the database tables.
     *
     * @return void
     */
    public function seed(): void
    {
        try {
            $driverId = $this->seedDriversTable();
            $vehicleId1 = $this->seedVehiclesTable($driverId, 'ABC-123');
            $vehicleId2 = $this->seedVehiclesTable($driverId, 'XYZ-456');
            $this->seedTripsTable($vehicleId1);
            $this->seedTripsTable($vehicleId2);
        } catch (Exception $e) {
            echo "Seeding failed: " . $e->getMessage() . "\n";
        }
    }

    private function seedDriversTable(): int
    {
        $driverData = [
            'username' => 'test_user',
            'password' => password_hash('Password@123', PASSWORD_DEFAULT),
        ];

        return $this->insertRow('drivers', $driverData);
    }

    private function seedVehiclesTable(int $driverId, string $licensePlate): int
    {
        $vehicleData = [
            'license_plate' => $licensePlate,
            'driver_id' => $driverId,
        ];

        return $this->insertRow('vehicles', $vehicleData);
    }

    private function seedTripsTable(int $vehicleId): void
    {
        $startDate = strtotime('2025-01-01');
        $endDate = strtotime('2025-01-31');
    
        $purposes = ['Business trip', 'Delivery', 'Maintenance check', 'Customer visit', 'Training', 'Supply run'];
        $locations = ['Downtown', 'Uptown', 'Industrial Area', 'City Center', 'Residential Zone', 'Business Park'];
    
        for ($date = $startDate; $date <= $endDate; $date = strtotime('+1 day', $date)) {
            $day = date('j', $date);
    
            if ($day >= 12 && $day <= 30) {
                $numTrips = rand(2, 4);
            } else {
                $numTrips = rand(1, 2);
            }
    
            for ($i = 0; $i < $numTrips; $i++) {
                $departureHour = rand(6, 10) + $i;
                $durationHours = rand(2, 6);
                $departureTime = mktime($departureHour, 0, 0, date('m', $date), date('d', $date), date('Y', $date));
                $arrivalTime = strtotime("+$durationHours hours", $departureTime);
    
                $tripData = [
                    'vehicle_id' => $vehicleId,
                    'start_km' => rand(100, 500),
                    'end_km' => rand(500, 1000),
                    'purpose' => $purposes[array_rand($purposes)],
                    'departure_time' => date('Y-m-d H:i:s', $departureTime),
                    'departure_location' => $locations[array_rand($locations)],
                    'arrival_time' => date('Y-m-d H:i:s', $arrivalTime),
                    'arrival_location' => $locations[array_rand($locations)],
                ];
    
                $this->insertRow('trips', $tripData);
            }
        }
    
        $departureTime = mktime(9, 0, 0, 1, 31, 2025);
    
        $ongoingTripData = [
            'vehicle_id' => $vehicleId,
            'start_km' => rand(100, 500),
            'end_km' => null,
            'purpose' => null,
            'departure_time' => date('Y-m-d H:i:s', $departureTime),
            'departure_location' => 'Ongoing Trip Start',
            'arrival_time' => null,
            'arrival_location' => null,
        ];
    
        $this->insertRow('trips', $ongoingTripData);
    }
    


    /**
     * Insert a row into a specified database table.
     *
     * @param string $tableName The name of the table.
     * @param array $data The data to insert as an associative array.
     * @return int The ID of the inserted row.
     * @throws Exception If the insert operation fails.
     */
    private function insertRow(string $tableName, array $data): int
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $query = "INSERT INTO {$tableName} ({$columns}) VALUES ({$placeholders})";

        $stmt = $this->db->prepare($query);

        foreach ($data as $column => $value) {
            $stmt->bindValue(":{$column}", $value);
        }

        if ($stmt->execute()) {
            echo "Seeded '{$tableName}' table successfully.\n";
            return (int)$this->db->lastInsertId();
        } else {
            echo "Failed to seed '{$tableName}' table.\n";
            throw new Exception("Failed to seed {$tableName} table.");
        }
    }
}

// Run the seeder
$seeder = new Seed();
$seeder->seed();
