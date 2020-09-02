<?php

namespace CapeAndBay\Shipyard;

use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use CapeAndBay\Shipyard\Services\LibraryService;

class ShipyardServiceProvider extends ServiceProvider
{
    const VERSION = '1.0.0';

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Where the route file lives, both inside the package and in the app (if overwritten).
     *
     * @var string
     */
    public $routeFilePath = '/routes/anchor-cms.php';

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'capeandbay');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'capeandbay');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->setupRoutes($this->app->router);

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Define the routes for the application.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function setupRoutes(Router $router)
    {
        // by default, use the routes file provided in vendor
        $routeFilePathInUse = __DIR__.$this->routeFilePath;

        $this->loadRoutesFrom($routeFilePathInUse);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/shipyard.php', 'shipyard');

        // Register the service the package provides.
        $this->app->singleton('shipyard', function ($app) {
            $lib = new LibraryService();
            return new Shipyard($lib);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['shipyard'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/shipyard.php' => config_path('shipyard.php'),
        ], 'config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/capeandbay'),
        ], 'shipyard.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/capeandbay'),
        ], 'shipyard.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/capeandbay'),
        ], 'shipyard.views');*/

        // Publishing the Migrations files.
        if (! class_exists('CreatePushNotificationsTable')) {
            $timestamp = date('Y_m_d_His', time());

            $this->publishes([
                __DIR__.'/../migrations/create_push_notifications_table.php.stub' => database_path("/migrations/{$timestamp}_create_push_notifications_table.php"),
            ], 'migrations');
        }

        // Registering package commands.
        // $this->commands([]);
    }
}
