<?php

namespace Fuguevit\Support\Providers;

use Fuguevit\Support\Console\Commands\Creators\HelperCreator;
use Fuguevit\Support\Console\Commands\MakeHelperCommand;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;
use Illuminate\Support\ServiceProvider;

class SupportServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/error-code.php' => config_path('error-code.php'),
            __DIR__.'/../../config/support.php'    => config_path('support.php')
        ], 'config');
    }

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        // Register bindings.
        $this->registerBindings();
        // Register make:helper command.
        $this->registerMakeHelperCommands();
        // Register commands.
        $this->commands(['command.helper.make']);
        
        $config_support_path = __DIR__.'/../../config/support.php';
        // Merge config.
        $this->mergeConfigFrom($config_support_path, 'support');
    }

    /**
     * Register the bindings.
     */
    protected function registerBindings()
    {
        // Filesystem
        $this->app->instance('FileSystem', new Filesystem());
        // Composer
        $this->app->bind('Composer', function($app) {
            return new Composer($app['FileSystem']);
        });
        // HelperCreator
        $this->app->singleton('HelperCreator', function($app) {
           return new HelperCreator($app['FileSystem']); 
        });
    }

    /**
     * Register the make:helper command.
     */
    protected function registerMakeHelperCommands()
    {
        $this->app['command.helper.make'] = $this->app->share(
            function($app) {
                return new MakeHelperCommand($app['HelperCreator']);
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    public function provides()
    {
        return [
            'command.helper.make'
        ];
    }

}