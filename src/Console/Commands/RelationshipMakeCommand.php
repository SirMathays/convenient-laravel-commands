<?php

namespace SirMathays\Console\Commands;

use SirMathays\Console\GeneratorCommand;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphOneOrMany;
use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
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
     * Supported relations. The current order shouldn't be edited!

     * @var (string|int)[][]
     */
    protected $supportedRelations = [
        ['class' => HasOneOrMany::class, 'count' => 3],
        ['class' => HasOneThrough::class],
        ['class' => HasManyThrough::class, 'count' => 2],
        ['class' => HasMany::class, 'count' => 2],
        ['class' => HasOne::class],
        ['class' => BelongsToMany::class, 'count' => 2],
        ['class' => BelongsTo::class],
        ['class' => MorphOneOrMany::class, 'simple' => true],
        ['class' => MorphToMany::class, 'simple' => true],
        ['class' => MorphMany::class, 'simple' => true],
        ['class' => MorphOne::class, 'simple' => true],
        ['class' => MorphPivot::class, 'simple' => true],
        ['class' => MorphTo::class, 'simple' => true]
    ];

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
        $relation = $this->getRelationDetails();
        $model = $this->getModel();

        $namespacedModel = Str::startsWith($model, '\\')
            ? trim($model, '\\')
            : $this->qualifyModel($model);

        $relName = Str::of($relation['name']);
        $relCount = $relation['count'];
        
        $namespacedInstanceClass = collect([
            "\\$namespacedModel" => in_array($relCount, [1, 3]),
            '\\' . Collection::class => $relCount > 1,
        ])->filter()->keys()->implode("|");

        $relationship = Str::plural(Str::camel($model), $relCount);

        $relationshipString = Str::of($relationship)->singular()->snake(' ');

        $replace = [
            '{{ namespacedInstanceClass }}' => $namespacedInstanceClass,
            '{{ namespacedModel }}' => $namespacedModel,
            '{{ namespacedRelationClass }}' => $relation['class'],
            '{{ relationClass }}' => (string) $relName,
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
        $relationName = $this->getRelation();

        return !is_null(collect($this->supportedRelations)->first(function ($relation) use ($relationName) {
            return class_basename($relation['class']) == $relationName;
        }));
    }

    /**
     * Parse relation from name.
     *
     * @return array
     */
    protected function getRelationDetails(): array
    {
        $relationName = $this->getRelation();

        $details = collect($this->supportedRelations)->first(function ($relation) use ($relationName) {
            return class_basename($relation['class']) == $relationName;
        });

        return [
            'name' => $relationName,
            'class' => Arr::get($details, 'class'),
            'count' => Arr::get($details, 'count', 1),
            'simple' => Arr::get($details, 'simple', false),
        ];
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
            $className = class_basename($relation['class']);

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
        return $this->getDefaultModelNamespace($rootNamespace);
    }

    /**
     * Return boolean value whether rich stub should be utilized.
     *
     * @return bool
     */
    protected function richStub(): bool
    {
        return !$this->getRelationDetails()['simple'];
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
