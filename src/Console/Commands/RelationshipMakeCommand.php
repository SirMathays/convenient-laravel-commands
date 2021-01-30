<?php

namespace SirMathays\Console\Commands;

use SirMathays\Console\GeneratorCommand;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection as SupportCollection;
use SirMathays\Relations\RelationBridge;
use Symfony\Component\Console\Input\InputOption;

class RelationshipMakeCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:relationship';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Relationship trait';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Relationship';

    /**
     * Supported relation bridges.
     *
     * @var SupportCollection
     */
    protected $supportedRelations = [
        \SirMathays\Relations\HasOneOrManyBridge::class,
        \SirMathays\Relations\HasOneThroughBridge::class,
        \SirMathays\Relations\HasOneBridge::class,
        \SirMathays\Relations\HasManyThroughBridge::class,
        \SirMathays\Relations\HasManyBridge::class,
        \SirMathays\Relations\BelongsToManyBridge::class,
        \SirMathays\Relations\BelongsToBridge::class,
        \SirMathays\Relations\MorphOneOrManyBridge::class,
        \SirMathays\Relations\MorphToManyBridge::class,
        \SirMathays\Relations\MorphManyBridge::class,
        \SirMathays\Relations\MorphOneBridge::class,
        \SirMathays\Relations\MorphToBridge::class,
    ];

    public function __construct(Filesystem $files)
    {
        parent::__construct($files);

        $this->supportedRelations = collect($this->supportedRelations)
            ->map(function ($bridgeClass) {
                return new $bridgeClass;
            });
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function handle()
    {
        if ($this->option('explicit') && (!$this->option('relation') || !$this->option('model'))) {
            $this->error("Provide both relation and model options when using explicit mode!");
            return false;
        }

        if (!$this->relationSupported()) {
            $this->error("Given relationship is not supported!");
            return false; 
        }

        parent::handle();
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {   
        $stub = parent::buildClass($name);

        return $this->richStub() ? $this->replaceStubVariables($stub) : $stub;
    }

    /**
     * Replace the model for the given stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function replaceStubVariables($stub)
    {
        $relation = $this->getRelationBridge();
        $model = $this->getModel();

        $namespacedModel = Str::startsWith($model, '\\')
            ? trim($model, '\\')
            : $this->qualifyModel($model);

        $relName = $relation->getNameAsStr();
        $relCount = $relation->getCount();
        
        $namespacedInstanceClass = $relation->getNamespacedInstanceClass($namespacedModel);

        $relationship = Str::of($model)->camel()->plural($relCount);
        $relationshipString = $relationship->singular()->snake(' ');

        $replace = [
            '{{ namespacedInstanceClass }}' => $namespacedInstanceClass,
            '{{ namespacedModel }}' => $namespacedModel,
            '{{ namespacedRelationClass }}' => $relation->getName(false),
            '{{ relationClass }}' => $relation->getName(),
            '{{ relationMethod }}' => (string) $relName->camel(),
            '{{ snakeUpperCaseClassName }}' => (string) Str::of($this->getNameInput())->snake()->upper(),
            '{{ relationship }}' => $relationship,
            '{{ ucRelationship }}' => ucfirst($relationship),
            '{{ relationshipString }}' => (string) $relationshipString,
            '{{ relationshipLongString }}' => (string) $relationshipString
                ->start($relCount <= 1 ? 'the ' : '')
                ->finish(' ' . Str::plural('relationship', $relCount))
        ];

        $stub = str_replace(
            array_keys($replace), array_values($replace), $stub
        );

        return str_replace(
            "use {$namespacedModel};\nuse {$namespacedModel};", "use {$namespacedModel};", $stub
        );
    }

    /**
     * Get either inputted relation or parsed from name.
     *
     * @return string
     */
    protected function getRelation(): string
    {
        return Str::studly(
            $this->option('relation') ?? Str::before($this->getNameInput(), $this->parseModelFromName())
        );
    }

    /**
     * Get either inputted model or parsed from name.
     *
     * @return string
     */
    protected function getModel(): string
    {
        return $this->option('model')
            ?? $this->parseModelFromName();
    }

    /**
     * Return boolean value whether given relation in name is supported.
     *
     * @return bool
     */
    protected function relationSupported(): bool
    {
        return !is_null($this->getRelationBridge());
    }

    /**
     * Parse relation from name.
     *
     * @return \SirMathays\Relations\RelationBridge|null
     */
    protected function getRelationBridge(): ?RelationBridge
    {
        return $this->supportedRelations
            ->first(function ($relation) {
                return $relation->getName() == $this->getRelation();
            });
    }

    /**
     * Parse model from name input.
     *
     * @return string
     */
    protected function parseModelFromName(): string
    {
        $nameInput = Str::of($this->getNameInput())->studly();

        foreach ($this->supportedRelations as $relation) {
            $className = $relation->getName();

            if ($nameInput->contains($className)) {
                return $nameInput->after($className)->studly()->singular();
            }
        }

        return $nameInput;
    }

    /**
     * {@inheritDoc}
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $this->getDefaultModelNamespace($rootNamespace)
            . '\\Relationships';
    }

    /**
     * Return boolean value whether rich stub should be utilized.
     *
     * @return bool
     */
    protected function richStub(): bool
    {
        return !$this->getRelationBridge()->isSimple();
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->richStub() 
            ? $this->getStubPath()
            : $this->resolveStubPath('/stubs/trait.stub');
    }

    /**
     * Get the relationship stub file for the generator.
     *
     * @return string
     */
    protected function getStubPath()
    {
        $relation = Str::kebab($this->getRelation());

        return file_exists($specialStub = $this->resolveStubPath("/stubs/relationship.$relation.stub"))
            ? $specialStub
            : $this->resolveStubPath('/stubs/relationship.stub');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['explicit', 'e', InputOption::VALUE_NONE, 'Skip implicit model and relation binding'],
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'The model that the relationship is based on'],
            ['relation', 'r', InputOption::VALUE_OPTIONAL, 'The relation that the relationship is based on'],
        ];
    }
}
