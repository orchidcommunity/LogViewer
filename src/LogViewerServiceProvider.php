<?php

namespace Orchid\LogViewer;

use Arcanedev\Support\PackageServiceProvider;
use Orchid\Platform\Kernel\Dashboard;
use Illuminate\Support\Facades\View;

class LogViewerServiceProvider extends PackageServiceProvider
{

    /**
     * Package name.
     *
     * @var string
     */
    protected $package = 'log-viewer';

    /**
     * Register the service provider.
     */
    public function register()
    {
        parent::register();

        $this->registerLogViewer();
        $this->registerAliases();

        $this->registerProviders([
            \Arcanedev\LogViewer\Providers\UtilitiesServiceProvider::class,
            RouteServiceProvider::class,
        ]);


        $this->loadViewsFrom(  __DIR__ . '/../resources/views', 'orchid/logs');
    }

    /**
     * Boot the service provider.
     */
    public function boot()
    {
        parent::boot();

        $dashboard = $this->app->make(Dashboard::class);


        $dashboard->permission->registerPermissions([
            'Systems' => [[
                'slug'        => 'dashboard.systems.logs',
                'description' => trans('cms::permission.systems.logs'),
            ]],
        ]);

        View::composer('dashboard::layouts.dashboard', function () use ($dashboard){

            $dashboard->menu->add('Systems', [
                'slug'       => 'logs',
                'icon'       => 'fa fa-bug',
                'route'      => route('dashboard.systems.logs.index'),
                'label'      => trans('cms::menu.logs'),
                'groupname'  => trans('cms::menu.errors'),
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
            \Arcanedev\LogViewer\Contracts\LogViewer::class,
        ];
    }

    /**
     * Register the log data class.
     */
    private function registerLogViewer()
    {
        $this->singleton(  \Arcanedev\LogViewer\Contracts\LogViewer::class,   \Arcanedev\LogViewer\LogViewer::class);

        // Registering the Facade
        $this->alias(
            $this->config()->get('log-viewer.facade', 'LogViewer'),
            \Arcanedev\LogViewer\Facades\LogViewer::class
        );
    }
}
