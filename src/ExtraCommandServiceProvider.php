<?php

namespace SirMathays;

use Illuminate\Support\ServiceProvider;

class ExtraCommandServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootConsoleCommands();
    }

    /**
     * Commands booter.
     *
     * @return void
     */
    protected function bootConsoleCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $commands = [
                \SirMathays\Console\Commands\ClassMakeCommand::class,
                \SirMathays\Console\Commands\InterfaceMakeCommand::class,
                \SirMathays\Console\Commands\RelationshipMakeCommand::class,
                \SirMathays\Console\Commands\TraitMakeCommand::class,
                \SirMathays\Console\Commands\ConcernMakeCommand::class,
            ];

            // If spatie query builder package exists.
            if (interface_exists('Spatie\QueryBuilder\Sorts\Sort')) {
                $commands[] = \SirMathays\Console\Commands\QuerySortMakeCommand::class;
            }

            if (interface_exists('Spatie\QueryBuilder\Filters\Filter')) {
                $commands[] = \SirMathays\Console\Commands\QueryFilterMakeCommand::class;
            }

            $this->commands($commands);
        }
    }
}