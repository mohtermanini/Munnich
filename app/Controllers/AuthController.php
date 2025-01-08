<?php

namespace Mohte\DriverLogbook\Controllers;

use Utils\Session;
use Utils\Router;
use Mohte\DriverLogbook\Services\AuthService;
use Utils\View;

/**
 * AuthController
 * 
 * Handles user login and logout processes.
 */
class AuthController
{
    private AuthService $authService;

    /**
     * Constructor
     * 
     * @param AuthService $authService Handles authentication logic.
     */
    public function __construct()
    {
        $this->authService = new AuthService();
    }

    /**
     * Handle the login process.
     * 
     * @return void
     */
    public function store(): void
    {
        if (!isset($_POST['username'], $_POST['password'])) {
            Session::set('error_message', 'Please provide both username and password.');
            Router::redirect('login');
            return;
        }

        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        $user = $this->authService->authenticate($username, $password);

        if ($user) {
            Session::start();
            Session::set('user_id', $user['id']);
            Router::redirect('dashboard');
        } else {
            Session::set('error_message', 'Invalid username or password.');
            Router::redirect('login');
        }
    }

    /**
     * Render the login page.
     * 
     * @return void
     */
    public function edit(): void
    {
        $errorMessage = Session::get('error_message');
        Session::remove('error_message');
        View::render('login.php', ['errorMessage' => $errorMessage]);
    }

    /**
     * Handle the logout process.
     * 
     * @return void
     */
    public function destroy(): void
    {
        Session::destroy();
        Router::redirect('login');
    }
}
