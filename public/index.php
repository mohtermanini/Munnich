<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Utils\Session;
use Utils\Router;
use Utils\View;
use Utils\Config;

// Set the base URL for views
$baseUrl = Config::get('BASE_URL', '/');
View::setGlobal('baseUrl', $baseUrl);

// Set the application name as a global variable
$appName = Config::get('APP_NAME', 'Default Application');
View::setGlobal('appName', $appName);

// Set the path to the views directory
$viewsPath = realpath(__DIR__ . '/../resources/views/');
View::setGlobal('viewsPath', $viewsPath);

// Start the session
Session::start();

// Resolve the current route
Router::resolve();
