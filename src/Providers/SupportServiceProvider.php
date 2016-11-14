<?php

namespace Fuguevit\Support\Providers;

use Fuguevit\Support\Console\Commands\Creators\HelperCreator;
use Fuguevit\Support\Console\Commands\MakeHelperCommand;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use ReflectionClass;

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

        $helperDir = config('support.helper_path');

        // if is directory , load helpers
        if (app()['FileSystem']->isDirectory($helperDir)) {
            static::loadHelpersFrom($helperDir);
        }
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
     * Load & Register helpers.
     * @param $directory
     */
    public static function loadHelpersFrom($directory)
    {
        $helpers = app()['FileSystem']->allFiles($directory);
        foreach ($helpers as $helper) {
            static::registerMethods($helper);
        }
    }

    /**
     * @param $helper
     */
    public static function registerMethods($helper)
    {
        $helperClassFQN = static::buildClassFQN($helper);
        $reflector = new ReflectionClass($helperClassFQN);
        $methods = $reflector->getMethods();
        foreach ($methods as $method) {
            $methodHelper = function(...$params) use ($method) {
//                return $method->class::{$method->name}(...$params);
                return call_user_func([$method->class, $method->name], ...$params);
            };
            View::share($method->name, $methodHelper);
        }
    }

    /**
     * @param $helper
     * @return string
     */
    public static function buildClassFQN($helper)
    {
        $helperClassName = substr($helper, 0, -4); // Remove .php at the end of the file name
        return config('support.helper_namespace') . $helperClassName;
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