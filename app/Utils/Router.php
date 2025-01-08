<?php

namespace Utils;

use Middlewares\AuthMiddleware;
use Middlewares\CsrfMiddleware;
use Middlewares\GuestMiddleware;
use Middlewares\TrimMiddleware;
use Mohte\DriverLogbook\Controllers\AuthController;
use Mohte\DriverLogbook\Controllers\TripController;
use Mohte\DriverLogbook\Controllers\VehicleController;

/**
 * Router class for managing application routes.
 *
 * This class resolves application routes and applies the necessary middleware.
 */
class Router
{
    /**
     * Resolve the route and execute the corresponding controller action.
     *
     * @param string $defaultRoute The default route to resolve (default: 'dashboard').
     * @return void
     */
    public static function resolve(string $defaultRoute = 'dashboard'): void
    {
        $route = $_GET['page'] ?? $defaultRoute;

        switch ($route) {
            case 'login':
                Middleware::apply([GuestMiddleware::class]);
                $controller = new AuthController();
                $controller->edit();
                break;

            case 'authenticate':
                Middleware::apply([GuestMiddleware::class]);
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $controller = new AuthController();
                    $controller->store();
                } else {
                    self::redirect('login');
                }
                break;

            case 'logout':
                Middleware::apply([AuthMiddleware::class]);
                $controller = new AuthController();
                $controller->destroy();
                break;

            case 'dashboard':
                Middleware::apply([AuthMiddleware::class]);
                View::render('dashboard.php');
                break;

            case 'getTripsDataForLastSevenDays':
                Middleware::apply([AuthMiddleware::class]);
                $tripController = new TripController();
                $tripController->getTripsDataForLastSevenDays();
                break;

            case 'getTripDurationsData':
                Middleware::apply([AuthMiddleware::class]);
                $tripController = new TripController();
                $tripController->getTripDurationsData();
                break;

            case 'getTotalHoursTraveled':
                Middleware::apply([AuthMiddleware::class]);
                $tripController = new TripController();
                $tripController->getTotalHoursTraveled();
                break;

            case 'getVehicleUsageData':
                Middleware::apply([AuthMiddleware::class]);
                $vehicleController = new VehicleController();
                $vehicleController->getVehicleUsageData($_GET);
                break;

            case 'getOngoingTrips':
                Middleware::apply([AuthMiddleware::class]);
                $tripController = new TripController();
                $tripController->getOngoingTrips();
                break;

            case 'trips':
                Middleware::apply([AuthMiddleware::class]);
                $tripController = new TripController();
                $tripController->index();
                break;

            case 'getTrips':
                Middleware::apply([AuthMiddleware::class]);
                $tripController = new TripController();
                $tripController->getTrips();
                break;

            case 'getVehicles':
                Middleware::apply([AuthMiddleware::class]);
                $vehicleController = new VehicleController();
                $vehicleController->getVehicles();
                break;

            case 'endTrip':
                Middleware::apply([AuthMiddleware::class, CsrfMiddleware::class]);
                $tripController = new TripController();
                $tripController->endTrip();
                break;

            case 'startTrip':
                Middleware::apply([AuthMiddleware::class, CsrfMiddleware::class]);
                $tripController = new TripController();
                $tripController->startTrip();
                break;

            case 'deleteTrip':
                Middleware::apply([AuthMiddleware::class]);
                $tripController = new TripController();
                $tripController->deleteTrip();
                break;

            default:
                http_response_code(404);
                echo "Page not found.";
                break;
        }
    }

    /**
     * Redirect to a specific route.
     *
     * @param string $route The route to redirect to.
     * @param int $statusCode The HTTP status code for the redirect (default: 302).
     * @return void
     */
    public static function redirect(string $route, int $statusCode = 302): void
    {
        $baseUrl = Config::get('BASE_URL', '/');
        header("Location: $baseUrl" . "index.php?page=$route", true, $statusCode);
        exit;
    }
}
