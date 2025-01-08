<?php

namespace Middlewares;

use Utils\Session;
use Utils\Router;

class AuthMiddleware
{
    /**
     * Ensure the user is authenticated.
     *
     * @return void
     */
    public static function handle(): void
    {
        if (!Session::get('user_id')) { // Check if user_id exists in the session
            Router::redirect('login');
        }
    }
}
