<?php

namespace Mohte\DriverLogbook\Models;

class Vehicle {
    public int $id;
    public string $licensePlate;
    public int $driverId;
    public string $createdAt;

    public function __construct(int $id, string $licensePlate, int $driverId, string $createdAt) {
        $this->id = $id;
        $this->licensePlate = $licensePlate;
        $this->driverId = $driverId;
        $this->createdAt = $createdAt;
    }
}
