<?php

namespace SirMathays\Console;

use Illuminate\Console\GeneratorCommand as ConsoleGeneratorCommand;
use Illuminate\Support\Str;

abstract class GeneratorCommand extends ConsoleGeneratorCommand
{
    /**
     * Return path to stub used for generation.
     *
     * @return string
     */
    protected function getStubPath()
    {
        return (string) Str::of(class_basename(static::class))
            ->before('MakeCommand')
            ->kebab()
            ->start('/stubs/')
            ->finish('.stub');
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__ . $stub;
    }

    /**
     * Get the default model namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultModelNamespace($rootNamespace)
    {
        return is_dir(app_path('Models'))
            ? $rootNamespace . '\\Models\\Relationships'
            : $rootNamespace . '\\Relationships';
    }

    /**
     * {@inheritDoc}
     */
    protected function getStub()
    {
        return $this->resolveStubPath($this->getStubPath());
    }
}