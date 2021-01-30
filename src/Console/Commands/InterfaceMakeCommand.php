<?php

namespace SirMathays\Console\Commands;

use SirMathays\Console\GeneratorCommand;

class InterfaceMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:interface';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new PHP interface';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Interface';

    /**
     * Return path to stub used for generation.
     *
     * @return string
     */
    protected function getStubPath()
    {
        return 'stubs/interface.stub';
    }
}