<?php

namespace Laragento\Cms\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Laragento\Cms\Repositories\BlockRepository;
use Laragento\Cms\Repositories\BlockRepositoryInterface;
use Laragento\Cms\Repositories\BlockTypeRepository;
use Laragento\Cms\Repositories\BlockTypeRepositoryInterface;
use Laragento\Cms\Repositories\PageRepository;
use Laragento\Cms\Repositories\PageRepositoryInterface;

class CmsServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->publishAssets();
        $this->registerFactories();
        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');
        $this->loadRoutesFrom(__DIR__ . '/Http/routes.php');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(BlockTypeRepositoryInterface::class, BlockTypeRepository::class);
        $this->app->bind(BlockRepositoryInterface::class, BlockRepository::class);
        $this->app->bind(PageRepositoryInterface::class, PageRepository::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('cms.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'cms'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/cms');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/cms';
        }, \Config::get('view.paths')), [$sourcePath]), 'cms');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/cms');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'cms');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'cms');
        }
    }

    /**
     * Register an additional directory of factories.
     * 
     * @return void
     */
    public function registerFactories()
    {
        if (! app()->environment('production')) {
            app(Factory::class)->load(__DIR__ . '/../Database/factories');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    protected function publishAssets()
    {
        $this->publishes([
            __DIR__.'/Assets' =>  public_path('modules/')],'public');
    }
}
