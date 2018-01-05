<?php

Route::group([
    'middleware' => config('platform.middleware.private'),
    'prefix'     => \Orchid\Platform\Kernel\Dashboard::prefix('/systems'),
    'namespace'  => 'Orchid\LogViewer',
],
    function (\Illuminate\Routing\Router $router) {
        $router->resource('logs', 'LogController', [
            'names' => [
                'index'    => 'dashboard.systems.logs.index',
                'show'     => 'dashboard.systems.logs.show',
            ]
        ]);

    });
