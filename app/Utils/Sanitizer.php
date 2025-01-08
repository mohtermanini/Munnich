<?php

namespace Utils;

/**
 * Sanitizer class for input sanitization.
 *
 * This class provides utilities to sanitize input data, such as strings,
 * to enhance security by preventing XSS attacks.
 */
class Sanitizer
{
    /**
     * Sanitize a string to prevent XSS attacks.
     *
     * This method trims the string and escapes special characters using
     * `htmlspecialchars` to ensure that malicious scripts are neutralized.
     *
     * @param string $value The input string to sanitize.
     * @return string The sanitized string.
     */
    public static function sanitizeString(string $value): string
    {
        return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }
}
