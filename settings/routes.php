<?php declare(strict_types=1);

use NursingLog\Application\Controller;

return static function(FastRoute\RouteCollector $routeCollector) {
    $routeCollector->addRoute(
        'GET',
        '/',
        [Controller\Index::class, 'get']
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
};
