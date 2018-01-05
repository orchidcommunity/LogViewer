<?php

namespace Orchid\LogViewer;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Orchid\Platform\Kernel\Dashboard;

class LogViewerServiceProvider extends ServiceProvider
{

    /**
     * Boot the service provider.
     */
    public function boot()
    {
        $this->loadRoutesFrom(realpath(__DIR__ . '/../routes/route.php'));
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'orchid/logs');

        $dashboard = $this->app->make(Dashboard::class);

        $dashboard->permission->registerPermissions([
            'Systems' => [
                [
                    'slug'        => 'dashboard.systems.logs',
                    'description' => 'Log Viewer',
                ],
            ],
        ]);

        View::composer('dashboard::layouts.dashboard', function () use ($dashboard) {

            $dashboard->menu->add('Systems', [
                'slug'       => 'logs',
                'icon'       => 'fa fa-bug',
                'route'      => route('dashboard.systems.logs.index'),
                'label'      => 'Log Viewer',
                'groupname'  => 'Errors',
                'childs'     => false,
                'divider'    => false,
                'permission' => 'dashboard.systems.logs',
                'sort'       => 500,
            ]);

        });

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            \Arcanedev\LogViewer\LogViewerServiceProvider::class,
        ];
    }
}
