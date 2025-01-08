<?php

namespace Mohte\DriverLogbook\Models;

class Driver {
    public int $id;
    public string $username;
    public string $password;
    public string $createdAt;

    public function __construct(int $id, string $username, string $password, string $createdAt) {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->createdAt = $createdAt;
    }
}
