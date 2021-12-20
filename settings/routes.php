<?php declare(strict_types=1);

use Milkbar\Application\Controller;

return static function(FastRoute\RouteCollector $routeCollector) {
    $routeCollector->addRoute(
        'GET',
        '/',
        [Controller\Authentication::class, 'renderLoginPage']
    );
    $routeCollector->addRoute(
        'GET',
        '/logout',
        [Controller\Authentication::class, 'logout']
    );
    $routeCollector->addRoute(
        'POST',
        '/login',
        [Controller\Authentication::class, 'login']
    );
    $routeCollector->addRoute(
        'GET',
        '/stats',
        [Controller\Stats::class, 'renderPage']
    );
    $routeCollector->addRoute(
        'GET',
        '/settings',
        [Controller\Settings::class, 'renderPage']
    );
    $routeCollector->addRoute(
        'POST',
        '/settings',
        [Controller\Settings::class, 'update']
    );
    $routeCollector->addRoute(
        'GET',
        '/dashboard',
        [Controller\Dashboard::class, 'get']
    );
    $routeCollector->addRoute(
        'GET',
        '/api/session/count',
        [Controller\Session::class, 'getCount']
    );
    $routeCollector->addRoute(
        'POST',
        '/api/session',
        [Controller\Session::class, 'post']
    );
    $routeCollector->addRoute(
        'GET',
        '/api/session',
        [Controller\Session::class, 'get']
    );
    $routeCollector->addRoute(
        'DELETE',
        '/api/session/{id:.+}',
        [Controller\Session::class, 'delete']
    );
    $routeCollector->addRoute(
        'PUT',
        '/api/session/{id:.+}',
        [Controller\Session::class, 'put']
    );
};
