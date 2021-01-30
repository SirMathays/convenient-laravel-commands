<?php

namespace SirMathays;

use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \SirMathays\Console\Commands\ClassMakeCommand::class,
                \SirMathays\Console\Commands\InterfaceMakeCommand::class,
                \SirMathays\Console\Commands\QueryFilterMakeCommand::class,
                \SirMathays\Console\Commands\QuerySortMakeCommand::class,
                \SirMathays\Console\Commands\RelationshipMakeCommand::class,
                \SirMathays\Console\Commands\TraitMakeCommand::class,
            ]);
        }
    }
}