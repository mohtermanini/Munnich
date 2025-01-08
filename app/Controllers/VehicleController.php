<?php

namespace Mohte\DriverLogbook\Controllers;

use Mohte\DriverLogbook\Services\VehicleService;
use Utils\Response;

class VehicleController
{
    private $vehicleService;

    public function __construct()
    {
        $this->vehicleService = new VehicleService();
    }

    /**
     * Get a list of vehicles.
     * 
     * @return void
     */
    public function getVehicles(): void
    {
        $vehicles = $this->vehicleService->getVehicles();

        Response::json($vehicles);
    }

    /**
     * Get vehicle usage data based on optional date range filters.
     * 
     * @param array $request Contains 'fromDate' and 'toDate' if provided.
     * @return void
     */
    public function getVehicleUsageData(array $request): void
    {
        $fromDate = $request['fromDate'] ?? null;
        $toDate = $request['toDate'] ?? null;
        $vehicleUsageData = $this->vehicleService->getVehicleUsage($fromDate, $toDate);

        Response::json($vehicleUsageData);
    }
}
