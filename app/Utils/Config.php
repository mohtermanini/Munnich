<?php

namespace Utils;

/**
 * Config class for managing environment variables from a .env file.
 *
 * This class provides functionality to load and retrieve environment variables
 * from a .env file located in the root directory of the application.
 */
class Config
{
    /**
     * @var array|null Holds the parsed .env file as an associative array.
     */
    private static ?array $env = null;

    /**
     * Load the .env file into an associative array.
     *
     * @throws \Exception If the .env file is not found.
     */
    private static function loadEnv(): void
    {
        if (self::$env === null) {
            $filePath = __DIR__ . '/../../.env';

            if (!file_exists($filePath)) {
                throw new \Exception('.env file not found.');
            }

            self::$env = parse_ini_file($filePath);
        }
    }

    /**
     * Get a value from the .env file.
     *
     * @param string $key The key to retrieve.
     * @param string|null $default A default value if the key does not exist.
     * @return string|null The value from the .env file or the default value.
     */
    public static function get(string $key, ?string $default = null): ?string
    {
        self::loadEnv();
        return self::$env[$key] ?? $default;
    }
}
