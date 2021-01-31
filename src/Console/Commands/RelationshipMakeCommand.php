<?php

namespace SirMathays\Console\Commands;

use Exception;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use SirMathays\Console\GeneratorCommand;
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
     * @var array
     */
    protected $relationBridges = [
        \SirMathays\Relations\BelongsToManyBridge::class,
        \SirMathays\Relations\BelongsToBridge::class,
        \SirMathays\Relations\HasManyThroughBridge::class,
        \SirMathays\Relations\HasManyBridge::class,
        \SirMathays\Relations\HasOneThroughBridge::class,
        \SirMathays\Relations\HasOneBridge::class,
        \SirMathays\Relations\MorphedByManyBridge::class,
        \SirMathays\Relations\MorphToManyBridge::class,
        \SirMathays\Relations\MorphManyBridge::class,
        \SirMathays\Relations\MorphOneBridge::class,
    ];

    /**
     * @var RelationBridge
     */
    protected $relation;

    /**
     * Generator command constructor.
     *
     * @param \Illuminate\Filesystem\Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct($files);
    }

    /**
     * {@inheritDoc}
     */
    public function handle()
    {
        $explicit = $this->option('explicit');
        $errorText = "Both relation and model(s) must be provided when using explicit mode!";

        // If explicit mode is used but relation or model is not given as options.
        if ($explicit && (!$this->option('relation') || !$this->option('model'))) {
            return $this->error($errorText);
        }

        try { $this->setRelation(); } 
        catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }

        // If explicit mode is used but seond model is not given as an option and the relation requires it.
        if ($this->relation->getModelCount() > 1 && $explicit && !$this->option('second-model')) {
            return $this->error($errorText);
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

        return $this->replaceStubVariables($stub);
    }

    /**
     * Get model name.
     *
     * @return string
     */
    public function getModel(string $choice = null): string
    {
        $option = 'model';

        if (!in_array($choice, $which = [null, 'second'])) {
            $choice = null;
        }

        if (!is_null($choice)) {
            $option = "{$choice}-{$option}";
        }

        return Str::of(
            $this->option($option) ??
                Arr::get(
                    $this->relation->getModelsFromName($this->getNameInput()),
                    array_search($choice, $which)
                )
        )->singular()->studly();
    }

    /**
     * Get qualified model name.
     *
     * @param string $choice
     * @return string
     */
    public function getQualifiedModel(string $choice = null): string
    {
        if (is_null($model = $this->getModel($choice))) {
            return '';
        }

        return Str::startsWith($model, '\\')
            ? trim($model, '\\')
            : $this->qualifyModel($model);   
    }

    /**
     * Get second model name.
     *
     * @return string
     */
    public function getSecondModel(): string
    {
        return $this->getModel('second');
    }

    /**
     * Replace the model for the given stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function replaceStubVariables($stub)
    {
        $relation = $this->relation;
        $model = $this->getModel();
        $namespacedModel = $this->getQualifiedModel();

        $relName = $relation->getNameAsStr();
        $relCount = $relation->getCount();

        $namespacedRelationshipInstanceClass = $relation->returnsCollection()
            ? '\\' . Collection::class
            : "\\$namespacedModel";

        $relationship = Str::of($model)->camel()->plural($relCount);
        $relationshipString = $relationship->singular()->snake(' ');

        $replace = [
            '{{ model }}' => $model,
            '{{ namespacedModel }}' => $namespacedModel,
            '{{ secondModel }}' => $this->getModel('second'),
            '{{ namespacedSecondModel }}' => $this->getQualifiedModel('second'),
            '{{ relationMethod }}' => (string) $relName->camel(),
            '{{ relationClass }}' => $relation->getClassName(),
            '{{ namespacedRelationClass }}' => $relation->getClassName(false),
            '{{ namespacedRelationshipInstanceClass }}' => $namespacedRelationshipInstanceClass,
            '{{ snakeUpperCaseClassName }}' => (string) Str::of($this->getNameInput())->snake()->upper(),
            '{{ relationship }}' => $relationship,
            '{{ ucRelationship }}' => ucfirst($relationship),
            '{{ relationshipString }}' => (string) $relationshipString,
            '{{ relationshipLongString }}' => (string) $relationshipString
                ->start($relCount <= 1 ? 'the ' : '')
                ->finish(' ' . Str::plural('relationship', $relCount))
        ];

        $stub = str_replace(
            array_keys($replace),
            array_values($replace),
            $stub
        );

        return str_replace(
            "use {$namespacedModel};\nuse {$namespacedModel};",
            "use {$namespacedModel};",
            $stub
        );
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
     * Undocumented function
     *
     * @return string
     */
    protected function getStubPath(): string
    {
        return '/stubs/' . $this->relation->getStubName();
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
            ['second-model', 's', InputOption::VALUE_OPTIONAL, 'The model that the relationship possibly uses'],
            ['relation', 'r', InputOption::VALUE_OPTIONAL, 'The relation that the relationship is based on'],
        ];
    }

    /**
     * Set relation.
     *
     * @return void
     * @throws \Exception
     */
    protected function setRelation(): void
    {
        foreach ($this->relationBridges as $bridgeClass) {
            $instance = new $bridgeClass;

            if ($this->option('relation')) {
                if ($this->option('relation') == $instance->getName()) {
                    $this->relation = $instance;
                    break;
                }
            } else if ($instance->matchesRelationshipName($this->getNameInput())) {
                $this->relation = $instance;
                break;
            }
        }

        if (is_null($this->relation)) {
            throw new Exception("Invalid relationship name given!");
        }
    }
}