<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Convenient Extra Commands
    |--------------------------------------------------------------------------
    |
    | Here you can set the boolean value to each command class to determine 
    | whether a command should appear on the artisan command list.
    |
    */

    /**
     * Commands for creating PHP OOP types
     */
    SirMathays\Convenience\Console\Commands\ClassMakeCommand::class             => true,
    SirMathays\Convenience\Console\Commands\InterfaceMakeCommand::class         => true,
    SirMathays\Convenience\Console\Commands\TraitMakeCommand::class             => true,

    /**
     * Commands for Model development
     */
    SirMathays\Convenience\Console\Commands\RelationshipMakeCommand::class      => true,
    SirMathays\Convenience\Console\Commands\ScopeMakeCommand::class             => true,

    /**
     * Other Commands
     */
    SirMathays\Convenience\Console\Commands\ConcernMakeCommand::class           => true,
    SirMathays\Convenience\Console\Commands\ContractMakeCommand::class          => true,
    SirMathays\Convenience\Console\Commands\GeneratorCommandMakeCommand::class  => true,

    /**
     * Commands for creating Spatie's QueryBuilder classes
     */
    SirMathays\Convenience\Console\Commands\QueryFilterMakeCommand::class       => true,
    SirMathays\Convenience\Console\Commands\QuerySortMakeCommand::class         => true
];