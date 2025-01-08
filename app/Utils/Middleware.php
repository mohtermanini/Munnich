<?php

namespace Utils;

/**
 * Middleware class for managing and applying middleware logic.
 *
 * This class provides functionality to apply middleware classes dynamically
 * by invoking their `handle` methods.
 */
class Middleware
{
    /**
     * Apply the given middleware classes.
     *
     * @param array $middlewares List of fully qualified middleware class names to apply.
     * @throws \Exception If a middleware class does not exist or is invalid.
     * @return void
     */
    public static function apply(array $middlewares): void
    {
        foreach ($middlewares as $middleware) {
            if (class_exists($middleware)) {
                if (method_exists($middleware, 'handle')) {
                    call_user_func([$middleware, 'handle']);
                } else {
                    throw new \Exception("Middleware {$middleware} does not have a valid method (handle).");
                }
            } else {
                throw new \Exception("Middleware {$middleware} does not exist.");
            }
        }
    }
}
