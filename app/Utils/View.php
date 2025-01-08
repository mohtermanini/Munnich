<?php

namespace Utils;

/**
 * View class for managing and rendering views.
 *
 * This class provides functionality to set global variables accessible to all views
 * and render specific views with provided data.
 */
class View
{
    /**
     * @var array Stores global variables accessible to all views.
     */
    private static array $globals = [];

    /**
     * Set a global variable for all views.
     *
     * @param string $key The name of the global variable.
     * @param mixed $value The value of the global variable.
     * @return void
     */
    public static function setGlobal(string $key, $value): void
    {
        self::$globals[$key] = $value;
    }

    /**
     * Render a view file with the provided data.
     *
     * @param string $viewPath The relative path to the view file (e.g., 'dashboard.php').
     * @param array $data An associative array of data to pass to the view.
     * @throws \Exception If the view file does not exist.
     * @return void
     */
    public static function render(string $viewPath, array $data = []): void
    {
        $data = array_merge(self::$globals, $data);

        extract($data);

        $fullPath = __DIR__ . '/../../resources/views/' . $viewPath;

        if (!file_exists($fullPath)) {
            throw new \Exception("View file not found: {$viewPath}");
        }

        require $fullPath;
    }
}
