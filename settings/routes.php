<?php declare(strict_types=1);

use NursingLog\Application\Controller;

return static function(FastRoute\RouteCollector $routeCollector) {
    $routeCollector->addRoute(
        'GET',
        '/',
        [Controller\Login::class, 'get']
    );
    $routeCollector->addRoute(
        'GET',
        '/logout',
        [Controller\Login::class, 'destroy']
    );
    $routeCollector->addRoute(
        'POST',
        '/login',
        [Controller\Login::class, 'authenticate']
    );
    $routeCollector->addRoute(
        'GET',
        '/dashboard',
        [Controller\Dashboard::class, 'get']
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
