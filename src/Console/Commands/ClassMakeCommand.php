<?php

namespace SirMathays\Console\Commands;

use SirMathays\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class ClassMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:class';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new PHP class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Class';

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {;
        return $this->replaceClassType(
            parent::buildClass($name)
        );
    }

    /**
     * Replace the model for the given stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function replaceClassType($stub)
    {
        $replace = [
            '{{ classType }}' => $this->option('abstract') ? 'abstract class' : 'class',
        ];

        return str_replace(
            array_keys($replace),
            array_values($replace),
            $stub
        );
    }

    /**
     * Return path to stub used for generation.
     *
     * @return string
     */
    protected function getStubPath()
    {
        return 'stubs/class.stub';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['abstract', 'a', InputOption::VALUE_NONE, 'Create abstract class.']
        ];
    }
}