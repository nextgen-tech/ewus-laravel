<?php
declare(strict_types=1);

namespace Etermed\Laravel\Ewus;

use Etermed\Ewus\Contracts\Connection;
use Etermed\Ewus\Handler;
use Etermed\Laravel\Ewus\Commands\PasswordCommand;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->mergeConfig();
        $this->registerBindings();
    }

    /**
     * Merge user-defined config with default values.
     *
     * @return  void
     */
    private function mergeConfig(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/ewus.php', 'ewus');
    }

    /**
     * Register class bindings to container.
     *
     * @return  void
     */
    private function registerBindings(): void
    {
        $this->app->singleton(Connection::class, function ($app) {
            /** @var \Etermed\Laravel\Ewus\ConnectionManager */
            $manager = $app->make(ConnectionManager::class);

            return $manager->driver();
        });

        $this->app->singleton(Handler::class, function ($app) {
            /** @var \Illuminate\Contracts\Config\Repository */
            $config = $app->make('config');

            $handler = new Handler();
            $handler->setConnection($app->make(Connection::class));

            if ($config->get('ewus.sandbox_mode', false) === true) {
                $handler->enableSandboxMode();
            }

            return $handler;
        });
    }

    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/ewus.php' => $this->app->configPath('ewus.php'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands(PasswordCommand::class);
        }
    }

    /**
     * @inheritDoc
     *
     * @return string[]
     */
    public function provides(): array
    {
        return [
            Connection::class,
            Handler::class,
        ];
    }
}
