<?php

namespace Middlewares;

use Utils\Session;

class CsrfMiddleware
{
    /**
     * Generate a CSRF token for a specific form and store it in the session.
     *
     * @param string $formKey Unique key for the form.
     * @return string The generated CSRF token in the format "formKey:token".
     */
    public static function generate(string $formKey): string
    {
        $token = bin2hex(random_bytes(32));
        Session::set("csrfToken_$formKey", $token);
        return "$formKey:$token";
    }

    /**
     * Validate the CSRF token from the request.
     *
     * @return void
     * @throws \Exception If the CSRF token is invalid or missing.
     */
    public static function handle(): void
    {
        $rawInput = file_get_contents("php://input");
        $requestData = json_decode($rawInput, true);

        if (empty($requestData['csrfToken'])) {
            throw new \Exception("CSRF token not found in the request.");
        }

        $tokenParts = explode(':', $requestData['csrfToken'] ?? '');
        if (count($tokenParts) !== 2) {
            throw new \Exception("Invalid CSRF token format. Expected 'formKey:token'. Received: " . json_encode($requestData['csrfToken']));
        }

        [$formKey, $requestToken] = $tokenParts;
        $sessionToken = Session::get("csrfToken_$formKey");

        if (!$sessionToken || $sessionToken !== $requestToken) {
            throw new \Exception("Invalid CSRF token.");
        }
    }
}
