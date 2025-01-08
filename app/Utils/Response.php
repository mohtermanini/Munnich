<?php

namespace Utils;

/**
 * Response class for handling HTTP responses.
 *
 * This class provides functionality to send JSON responses with a specified
 * HTTP status code.
 */
class Response
{
    /**
     * Send a JSON response with the given data and status code.
     *
     * @param mixed $data The data to send as JSON.
     * @param int $statusCode The HTTP status code (default: 200).
     * @return void
     */
    public static function json($data, int $statusCode = 200): void
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }
}
