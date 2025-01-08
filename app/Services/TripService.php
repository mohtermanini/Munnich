<?php

namespace Mohte\DriverLogbook\Services;

use Config\Database;
use PDO;
use PDOException;

class TripService
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
     * Get trip details by ID.
     *
     * @param int $tripId
     * @return array|null
     */
    public function getTripById(int $tripId): ?array
    {
        $query = "
            SELECT 
                trips.id AS trip_id,
                trips.vehicle_id AS vehicle_id,
                vehicles.license_plate AS vehicle,
                DATE_FORMAT(trips.departure_time, '%Y-%m-%d') AS departure_date,
                DATE_FORMAT(trips.departure_time, '%H:%i') AS departure_time,
                DATE_FORMAT(trips.arrival_time, '%Y-%m-%d') AS arrival_date,
                DATE_FORMAT(trips.arrival_time, '%H:%i') AS arrival_time,
                trips.start_km,
                trips.end_km,
                trips.departure_location,
                trips.arrival_location,
                CASE 
                    WHEN trips.arrival_time IS NULL THEN 'In Progress'
                    ELSE 'Completed'
                END AS status
            FROM trips
            INNER JOIN vehicles ON trips.vehicle_id = vehicles.id
            WHERE trips.id = :tripId
        ";

        $statement = $this->db->prepare($query);
        $statement->bindParam(':tripId', $tripId, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Get a paginated list of trips with optional filters.
     *
     * @param int $page
     * @param int $limit
     * @param array $filters
     * @return array
     */
    public function getTrips(int $page, int $limit, array $filters = []): array
    {
        $offset = ($page - 1) * $limit;
        $whereClauses = [];
        $params = [];

        if (!empty($filters['fromDate'])) {
            $whereClauses[] = 'trips.departure_time >= :fromDate';
            $params[':fromDate'] = $filters['fromDate'];
        }

        if (!empty($filters['toDate'])) {
            $whereClauses[] = 'trips.departure_time <= :toDate';
            $params[':toDate'] = $filters['toDate'] . ' 23:59:59';
        }

        if (!empty($filters['status'])) {
            if ($filters['status'] === 'Completed') {
                $whereClauses[] = 'trips.arrival_time IS NOT NULL';
            } elseif ($filters['status'] === 'In Progress') {
                $whereClauses[] = 'trips.arrival_time IS NULL';
            }
        }

        if (!empty($filters['vehicle'])) {
            $whereClauses[] = 'vehicles.license_plate = :vehicle';
            $params[':vehicle'] = $filters['vehicle'];
        }

        $whereSQL = $whereClauses ? 'WHERE ' . implode(' AND ', $whereClauses) : '';

        $countQuery = "
            SELECT COUNT(*) as total
            FROM trips
            INNER JOIN vehicles ON trips.vehicle_id = vehicles.id
            $whereSQL
        ";
        $countStmt = $this->db->prepare($countQuery);
        foreach ($params as $key => $value) {
            $countStmt->bindValue($key, $value);
        }
        $countStmt->execute();
        $totalRecords = (int)$countStmt->fetch(PDO::FETCH_ASSOC)['total'];
        $totalPages = ceil($totalRecords / $limit);

        $query = "
            SELECT 
                trips.id AS trip_id,
                vehicles.license_plate AS vehicle,
                DATE_FORMAT(trips.departure_time, '%Y-%m-%d') AS departure_date,
                DATE_FORMAT(trips.departure_time, '%H:%i') AS departure_time,
                DATE_FORMAT(trips.arrival_time, '%Y-%m-%d') AS arrival_date,
                DATE_FORMAT(trips.arrival_time, '%H:%i') AS arrival_time,
                trips.start_km,
                trips.end_km,
                trips.departure_location,
                trips.arrival_location,
                trips.purpose,
                CASE 
                    WHEN trips.arrival_time IS NULL THEN 'In Progress'
                    ELSE 'Completed'
                END AS status
            FROM trips
            INNER JOIN vehicles ON trips.vehicle_id = vehicles.id
            $whereSQL
            ORDER BY trips.departure_time DESC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->db->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'data' => $records,
            'meta' => [
                'totalRecords' => $totalRecords,
                'totalPages' => $totalPages,
                'currentPage' => $page,
                'count' => count($records),
                'startIndex' => $offset + 1,
                'endIndex' => $offset + count($records),
            ],
        ];
    }

    /**
     * Get trips data for the last seven days.
     *
     * @return array
     */
    public function getTripsDataForLastSevenDays(): array
    {
        $today = date('Y-m-d');
        $dates = [];

        for ($i = 6; $i >= 0; $i--) {
            $dates[] = date('Y-m-d', strtotime($today . ' - ' . $i . ' days'));
        }

        $placeholders = implode(',', array_fill(0, count($dates), '?'));
        $query = "
            SELECT DATE(arrival_time) as trip_date, COUNT(*) as trips_count
            FROM trips
            WHERE DATE(arrival_time) IN ($placeholders)
              AND arrival_time IS NOT NULL
            GROUP BY trip_date
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute($dates);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $tripCounts = array_fill(0, 7, 0);
        $formattedData = [];

        foreach ($result as $row) {
            $dateIndex = array_search($row['trip_date'], $dates);
            if ($dateIndex !== false) {
                $tripCounts[$dateIndex] = $row['trips_count'];
            }
        }

        foreach ($dates as $index => $date) {
            $formattedDate = date('j M', strtotime($date));
            $formattedData[] = [
                'label' => $formattedDate,
                'value' => $tripCounts[$index],
            ];
        }

        return $formattedData;
    }

    /**
     * Get trip durations (max, min, avg).
     *
     * @return array
     */
    public function getTripDurations(): array
    {
        $query = "
            SELECT 
                MAX(TIMESTAMPDIFF(MINUTE, departure_time, arrival_time)) AS max_duration,
                MIN(TIMESTAMPDIFF(MINUTE, departure_time, arrival_time)) AS min_duration,
                AVG(TIMESTAMPDIFF(MINUTE, departure_time, arrival_time)) AS avg_duration
            FROM trips
            WHERE arrival_time IS NOT NULL
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return [
            'max' => $result['max_duration'] ?? 0,
            'min' => $result['min_duration'] ?? 0,
            'avg' => $result['avg_duration'] ?? 0,
        ];
    }

    /**
     * Get ongoing trips.
     *
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getOngoingTrips(int $page, int $limit): array
    {
        $offset = ($page - 1) * $limit;

        $countQuery = "
            SELECT COUNT(*) as total
            FROM trips
            INNER JOIN vehicles ON trips.vehicle_id = vehicles.id
            WHERE trips.arrival_time IS NULL
        ";
        $countStmt = $this->db->prepare($countQuery);
        $countStmt->execute();
        $totalRecords = (int)$countStmt->fetch(PDO::FETCH_ASSOC)['total'];
        $totalPages = ceil($totalRecords / $limit);

        $query = "
            SELECT 
                trips.id AS trip_id, 
                vehicles.license_plate AS vehicle, 
                DATE_FORMAT(trips.departure_time, '%Y-%m-%d') AS departure_date,
                DATE_FORMAT(trips.departure_time, '%H:%i') AS departure_time,
                trips.start_km,
                trips.departure_location, 
                CASE 
                    WHEN trips.arrival_time IS NULL THEN 'In Progress'
                    ELSE 'Completed'
                END AS status
            FROM trips
            INNER JOIN vehicles ON trips.vehicle_id = vehicles.id
            WHERE trips.arrival_time IS NULL
            ORDER BY trips.departure_time DESC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'data' => $records,
            'meta' => [
                'totalRecords' => $totalRecords,
                'totalPages' => $totalPages,
                'currentPage' => $page,
                'count' => count($records),
                'startIndex' => $offset + 1,
                'endIndex' => $offset + count($records),
            ],
        ];
    }

    /**
     * End a trip.
     *
     * @param int $tripId
     * @param string $endDate
     * @param string $endTime
     * @param string $endLocation
     * @param float $endKm
     * @param string $purpose
     * @return bool
     */
    public function endTrip(int $tripId, string $endDate, string $endTime, string $endLocation, float $endKm, string $purpose): bool
    {
        $query = "
            UPDATE trips
            SET 
                arrival_time = :arrivalTime,
                arrival_location = :arrivalLocation,
                end_km = :endKm,
                purpose = :purpose
            WHERE id = :tripId
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":arrivalTime", "$endDate $endTime");
        $stmt->bindValue(":arrivalLocation", $endLocation);
        $stmt->bindValue(":endKm", $endKm);
        $stmt->bindValue(":purpose", $purpose);
        $stmt->bindValue(":tripId", $tripId);

        return $stmt->execute();
    }

    /**
     * Get an ongoing trip by vehicle ID.
     *
     * @param int $vehicleId
     * @return array|null
     */
    public function getOngoingTripByVehicleId(int $vehicleId): ?array
    {
        $query = "
            SELECT * 
            FROM trips
            WHERE vehicle_id = :vehicleId
              AND arrival_time IS NULL
            LIMIT 1
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':vehicleId', $vehicleId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Start a trip.
     *
     * @param int $vehicleId
     * @param string $startDate
     * @param string $startTime
     * @param string $startLocation
     * @param float $startKm
     * @return bool
     */
    public function startTrip(int $vehicleId, string $startDate, string $startTime, string $startLocation, float $startKm): bool
    {
        $query = "
            INSERT INTO trips (vehicle_id, departure_time, departure_location, start_km)
            VALUES (:vehicleId, :departureTime, :startLocation, :startKm)
        ";

        $departureTime = "$startDate $startTime";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':vehicleId', $vehicleId, PDO::PARAM_INT);
        $stmt->bindValue(':departureTime', $departureTime, PDO::PARAM_STR);
        $stmt->bindValue(':startLocation', $startLocation, PDO::PARAM_STR);
        $stmt->bindValue(':startKm', $startKm, PDO::PARAM_STR);

        return $stmt->execute();
    }

    /**
     * Get total hours traveled grouped by vehicles and overall.
     *
     * @return array
     */
    public function getTotalHoursTraveled(): array
    {
        $query = "
            SELECT 
                vehicles.license_plate AS label,
                SUM(TIMESTAMPDIFF(MINUTE, departure_time, arrival_time) / 60) AS hours
            FROM trips
            INNER JOIN vehicles ON trips.vehicle_id = vehicles.id
            WHERE arrival_time IS NOT NULL
            GROUP BY vehicles.id
            ORDER BY hours DESC
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $vehicleHours = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $totalQuery = "
            SELECT 
                SUM(TIMESTAMPDIFF(MINUTE, departure_time, arrival_time) / 60) AS total_hours
            FROM trips
            WHERE arrival_time IS NOT NULL
        ";
        $totalStmt = $this->db->prepare($totalQuery);
        $totalStmt->execute();
        $totalHours = $totalStmt->fetch(PDO::FETCH_ASSOC)['total_hours'];

        if ($totalHours !== null) {
            $vehicleHours[] = [
                'label' => 'Total',
                'hours' => round($totalHours, 2),
            ];
        }

        return $vehicleHours;
    }

    /**
     * Delete a trip if authorized.
     *
     * @param int $tripId
     * @param int $userId
     * @return bool
     */
    public function deleteTrip(int $tripId, int $userId): bool
    {
        $trip = $this->getTripById($tripId);

        if (!$trip) {
            return false;
        }

        $vehicleId = $trip['vehicle_id'];
        $vehicleService = new VehicleService();
        $vehicle = $vehicleService->getVehicleById($vehicleId);

        if (!$vehicle || $vehicle['driver_id'] != $userId) {
            return false;
        }

        $deleteQuery = "DELETE FROM trips WHERE id = :tripId";
        $deleteStmt = $this->db->prepare($deleteQuery);
        $deleteStmt->bindValue(':tripId', $tripId, PDO::PARAM_INT);

        return $deleteStmt->execute();
    }
}
