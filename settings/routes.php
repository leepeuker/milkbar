<?php declare(strict_types=1);

use NursingLog\Controller;

return static function(FastRoute\RouteCollector $routeCollector) {
    $routeCollector->addRoute(
        'GET',
        '/',
        [Controller::class, 'get']
    );
};
