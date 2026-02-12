<?php

namespace Eheuristic\LaravelLogMonitor;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Eheuristic\LaravelLogMonitor\Console\Commands\SendLogReportCommand;

class LogMonitorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/log-monitor.php',
            'log-monitor'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish config file
        $this->publishes([
            __DIR__ . '/../config/log-monitor.php' => config_path('log-monitor.php'),
        ], 'log-monitor-config');

        // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                SendLogReportCommand::class,
            ]);
        }

        // Load views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'log-monitor');

        // Publish views
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/log-monitor'),
        ], 'log-monitor-views');

        // Assets
        $this->publishes([
            __DIR__ . '/../resources/assets' => public_path('vendor/log-monitor/assets'),
        ], 'log-monitor-assets');

        View::composer('log-monitor::emails.log-report', function ($view) {
            /*
            |--------------------------------------------------------------------------
            | Build base public path for package assets
            |--------------------------------------------------------------------------
            |
            | Assets are published to:
            | public/vendor/log-monitor/assets
            |
            */
            $basePath = asset('vendor/log-monitor/assets');

            /*
            |--------------------------------------------------------------------------
            | Map config image names to full public URLs
            |--------------------------------------------------------------------------
            */
            $assets = collect(config('log-monitor.assets', []))
                ->map(fn($file) => $basePath . '/' . $file)
                ->toArray();

            /*
            |--------------------------------------------------------------------------
            | Share all assets with the view
            |--------------------------------------------------------------------------
            */
            $view->with('assets', $assets);
        });
    }
}
