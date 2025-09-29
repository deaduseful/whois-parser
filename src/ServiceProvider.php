<?php

namespace DeadusefulWhoisParser;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as BaseProvider;

class ServiceProvider extends BaseProvider
{
    public const NAMESPACE = 'whois-parser';
    public const CONFIG_PATH = __DIR__ . DIRECTORY_SEPARATOR . 'config.php';
    public const RESOURCES_DIR = __DIR__ . DIRECTORY_SEPARATOR . 'resources';
    public const MIGRATIONS_DIR = __DIR__ . DIRECTORY_SEPARATOR . 'database';

    /** @var string[] */
    public const COMMANDS = [
        Command::class,
    ];

    public function register(): void
    {
        $this->mergeConfigFrom(self::CONFIG_PATH, self::NAMESPACE);
    }

    public function boot(): void
    {
        $this->loadMigrations();
        $this->loadTranslations();
        $this->mapRoutes();
        if ($this->app->runningInConsole()) {
            $this->getPublishes();
            $this->getCommands();
        } else {
            $this->loadViews();
        }
    }

    private function loadMigrations(): void
    {
        $this->loadMigrationsFrom(self::MIGRATIONS_DIR);
    }

    private function loadTranslations(): void
    {
        $path = self::RESOURCES_DIR . DIRECTORY_SEPARATOR . 'lang';
        $this->loadTranslationsFrom($path, self::NAMESPACE);
    }

    private function getPublishes(): void
    {
        $this->publishes([
            self::CONFIG_PATH => config_path(self::NAMESPACE . '.php'),
        ], 'config');
    }

    private function getCommands(): void
    {
        $this->commands(self::COMMANDS);
    }

    protected function mapRoutes(): void
    {
        $config = Config::get(self::NAMESPACE . '.routes');
        $prefix = $config['prefix'] ?? self::NAMESPACE;
        $name = $config['name'] ?? self::NAMESPACE . '.';
        $middleware = $config['middleware'] ?? ['web', 'auth'];
        Route::prefix($prefix)
            ->name($name)
            ->middleware($middleware)
            ->namespace(__NAMESPACE__)
            ->group(__DIR__ . DIRECTORY_SEPARATOR . 'routes.php');
    }

    private function loadViews(): void
    {
        $this->loadViewsFrom(self::RESOURCES_DIR . DIRECTORY_SEPARATOR . 'views', self::NAMESPACE);
    }
}
