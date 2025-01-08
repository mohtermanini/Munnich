<?php

namespace Utils;

/**
 * Session class for managing PHP sessions.
 *
 * This class provides utilities to handle session management, such as starting
 * a session, setting, retrieving, and removing session variables, and destroying
 * the session.
 */
class Session
{
    /**
     * Start a new session or resume the existing one.
     *
     * This method ensures the session is started only if no session is active.
     * It also configures session cookie and garbage collection lifetimes.
     *
     * @return void
     */
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.cookie_lifetime', 86400); // 24 hours
            ini_set('session.gc_maxlifetime', 86400);  // 24 hours
            session_start();
        }
    }

    /**
     * Set a session variable.
     *
     * @param string $key The key to store the value under.
     * @param mixed $value The value to store.
     * @return void
     */
    public static function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Get a session variable.
     *
     * @param string $key The key of the variable to retrieve.
     * @return mixed|null The value of the session variable, or null if it does not exist.
     */
    public static function get(string $key)
    {
        return $_SESSION[$key] ?? null;
    }

    /**
     * Remove a session variable.
     *
     * @param string $key The key of the variable to remove.
     * @return void
     */
    public static function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * Destroy the current session.
     *
     * This method clears all session data and destroys the session if it is active.
     *
     * @return void
     */
    public static function destroy(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
        }
    }
}
