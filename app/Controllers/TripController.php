<?php

namespace Mohte\DriverLogbook\Controllers;

use Mohte\DriverLogbook\Resources\TripResource;
use Mohte\DriverLogbook\Services\TripService;
use Mohte\DriverLogbook\Services\VehicleService;
use Utils\Response;
use Utils\Session;
use Utils\Validation;
use Utils\View;

class TripController
{
    private $tripService;
    private $vehicleService;

    public function __construct()
    {
        $this->tripService = new TripService();
        $this->vehicleService = new VehicleService();
    }

    /**
     * Display the trips view.
     * 
     * @return void
     */
    public function index(): void
    {
        View::render('trips.php', []);
    }

    /**
     * Get a paginated list of trips with optional filters.
     * 
     * @return void
     */
    public function getTrips(): void
    {
        $page = isset($_GET['currentPage']) ? (int)$_GET['currentPage'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;

        $filters = [
            'fromDate' => $_GET['fromDate'] ?? null,
            'toDate' => $_GET['toDate'] ?? null,
            'status' => $_GET['status'] ?? null,
            'vehicle' => $_GET['vehicle'] ?? null,
        ];

        $trips = $this->tripService->getTrips($page, $limit, $filters);

        Response::json([
            'data' => TripResource::format($trips['data']),
            'meta' => $trips['meta'],
        ]);
    }

    /**
     * Get data for trip durations (min, max, avg).
     * 
     * @return void
     */
    public function getTripDurationsData(): void
    {
        $durationsData = $this->tripService->getTripDurations();

        Response::json($durationsData);
    }

    /**
     * Get trip data for the last seven days.
     * 
     * @return void
     */
    public function getTripsDataForLastSevenDays(): void
    {
        $tripData = $this->tripService->getTripsDataForLastSevenDays();

        Response::json($tripData);
    }

    /**
     * Get ongoing trips.
     * 
     * @return void
     */
    public function getOngoingTrips(): void
    {
        $page = isset($_GET['currentPage']) ? (int)$_GET['currentPage'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $ongoingTrips = $this->tripService->getOngoingTrips($page, $limit);

        Response::json([
            'data' => TripResource::format($ongoingTrips['data']),
            'meta' => $ongoingTrips['meta'],
        ]);
    }

    /**
     * Get the total hours traveled for trips.
     * 
     * @return void
     */
    public function getTotalHoursTraveled(): void
    {
        $data = $this->tripService->getTotalHoursTraveled();

        Response::json($data);
    }

    /**
     * Start a new trip.
     * 
     * @return void
     */
    public function startTrip(): void
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $requiredFields = ['licensePlate', 'startDate', 'startTime', 'startLocation', 'startKm'];
        $validationErrors = Validation::requiredFields($data, $requiredFields);

        $lengthValidation = [
            'startLocation' => ['min' => 3, 'max' => 100],
            'licensePlate' => ['min' => 1, 'max' => 20]
        ];
        $validationErrors = array_merge($validationErrors, Validation::length($data, $lengthValidation));

        if (isset($data['startKm']) && (float)$data['startKm'] < 0) {
            $validationErrors['startKm'] = 'Initial odometer reading must be a non negative number.';
        }

        if (isset($data['startKm']) && (float)$data['startKm'] >= 10000) {
            $validationErrors['startKm'] = 'Initial odometer reading must less than 10000 km.';
        }

        if (!empty($validationErrors)) {
            Response::json(['errors' => $validationErrors], 400);
            return;
        }

        $vehicle = $this->vehicleService->getVehicleByLicensePlate($data['licensePlate']);
        $driverId = Session::get("user_id");

        if (!$vehicle) {
            $newVehicleId = $this->vehicleService->createVehicle($data['licensePlate'], $driverId);
            if (!$newVehicleId) {
                Response::json(['error' => 'Failed to create a new vehicle.'], 400);
                return;
            }
            $vehicle = [
                'id' => $newVehicleId,
                'license_plate' => $data['licensePlate'],
                'driver_id' => $driverId
            ];
        }

        if ($vehicle['driver_id'] !== $driverId) {
            Response::json(['error' => 'This vehicle does not belong to the authenticated driver.'], 403);
            return;
        }

        $ongoingTrip = $this->tripService->getOngoingTripByVehicleId($vehicle['id']);
        if ($ongoingTrip) {
            Response::json(['error' => 'An ongoing trip already exists for this vehicle.'], 400);
            return;
        }

        $result = $this->tripService->startTrip(
            $vehicle['id'],
            $data['startDate'],
            $data['startTime'],
            $data['startLocation'],
            round($data['startKm'], 2)
        );

        if ($result) {
            Response::json(["success" => true]);
        } else {
            Response::json(["success" => false], 400);
        }
    }

    /**
     * Update an existing trip by ending it.
     * 
     * @return void
     */
    public function endTrip(): void
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $trip = $this->tripService->getTripById($data['tripId']);
        if (!$trip) {
            Response::json(['error' => 'Trip not found'], 404);
            return;
        }

        $vehicle = $this->vehicleService->getVehicleById($trip['vehicle_id']);
        $driverId = Session::get("user_id");

        if ($vehicle['driver_id'] !== $driverId) {
            Response::json(['error' => 'This trip does not belong to the authenticated driver.'], 403);
            return;
        }

        $requiredFields = ['tripId', 'endDate', 'endTime', 'endLocation', 'endKm'];
        $validationErrors = Validation::requiredFields($data, $requiredFields);

        $lengthValidation = [
            'endLocation' => ['min' => 3, 'max' => 100],
            'purpose' => ['min' => 0, 'max' => 255]
        ];

        if (isset($data['endKm']) && (float)$data['endKm'] >= 10000) {
            $validationErrors['endKm'] = 'Final odometer reading must less than 10000 km.';
        }
        
        $validationErrors = array_merge($validationErrors, Validation::length($data, $lengthValidation));

        $startDate = $trip['departure_date'] ?? null;
        $startTime = $trip['departure_time'] ?? null;
        if ($startDate && $startTime && $data['endDate'] && $data['endTime']) {
            $startDateTime = strtotime("$startDate $startTime");
            $endDateTime = strtotime($data['endDate'] . ' ' . $data['endTime']);
            if ($endDateTime <= $startDateTime) {
                $validationErrors['endDate'] = 'Arrival time must be after the departure time.';
            }
        }

        $startOdometer = $trip['start_km'] ?? null;
        if ($startOdometer !== null && (float)$data['endKm'] < (float)$startOdometer) {
            $validationErrors['endKm'] = 'Final odometer reading must be greater than or equal to the start odometer reading.';
        }

        if (!empty($validationErrors)) {
            Response::json(['errors' => $validationErrors], 400); // Return validation errors
            return;
        }

        $result = $this->tripService->endTrip(
            $data['tripId'],
            $data['endDate'],
            $data['endTime'],
            $data['endLocation'],
            round($data['endKm'], 2),
            $data['purpose']
        );

        if ($result) {
            Response::json(["success" => true]);
        } else {
            Response::json(["success" => false], 400);
        }
    }

    /**
     * Delete a trip.
     * 
     * @return void
     */
    public function deleteTrip(): void
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $tripId = $data['tripId'] ?? null;

        if (!$tripId) {
            Response::json(['error' => 'Trip ID is required'], 400);
            return;
        }

        $userId = Session::get('user_id');

        $result = $this->tripService->deleteTrip($tripId, $userId);

        if ($result) {
            Response::json(['success' => true]);
        } else {
            Response::json(['error' => 'You are not authorized to delete this trip or the trip does not exist.'], 403);
        }
    }
}
