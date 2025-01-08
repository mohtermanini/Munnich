<?php

namespace Mohte\DriverLogbook\Models;

class Trip {
    public int $id;
    public int $driverId;
    public int $vehicleId;
    public int $startKm;
    public int $endKm;
    public string $purpose;
    public string $departureTime;
    public string $departureLocation;
    public string $arrivalTime;
    public string $arrivalLocation;
    public string $createdAt;

    public function __construct(
        int $id,
        int $driverId,
        int $vehicleId,
        int $startKm,
        int $endKm,
        string $purpose,
        string $departureTime,
        string $departureLocation,
        string $arrivalTime,
        string $arrivalLocation,
        string $createdAt
    ) {
        $this->id = $id;
        $this->driverId = $driverId;
        $this->vehicleId = $vehicleId;
        $this->startKm = $startKm;
        $this->endKm = $endKm;
        $this->purpose = $purpose;
        $this->departureTime = $departureTime;
        $this->departureLocation = $departureLocation;
        $this->arrivalTime = $arrivalTime;
        $this->arrivalLocation = $arrivalLocation;
        $this->createdAt = $createdAt;
    }
}
