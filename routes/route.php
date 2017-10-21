<?php

$this->group([
    'middleware' => ['web', 'dashboard', 'access'],
    'prefix'     => 'dashboard/systems',
    'namespace'  => 'Orchid\LogViewer\Http\Controllers',
],
    function (\Illuminate\Routing\Router $router) {
        $router->resource('logs', 'LogController', [
            'names' => [
                'index'    => 'dashboard.systems.logs.index',
                'show'     => 'dashboard.systems.logs.show',
            ]
        ]);

    });
