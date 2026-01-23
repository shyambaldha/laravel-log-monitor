<?php

namespace Eheuristic\LaravelLogMonitor;

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
    }
}
