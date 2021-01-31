<?php

namespace SirMathays\Relations;

use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use Illuminate\Support\Collection;

abstract class RelationBridge
{
    /**
     * The relation class name.
     * 
     * @var string
     */
    protected static $relationClassName;

    /**
     * Whether the relationship instance is a Collection of related Models 
     * or a single Model.
     * 
     * @var bool
     */
    protected static $returnsCollection = true;

    /**
     * How many Models must be defined for the relation.
     *
     * @var int
     */
    protected static $modelCount = 1;

    /**
     * The stub affix that will be appended to the stub name.
     * E.g. 'morph' = 'relationship.morph.stub'
     * 
     * @var string|null
     */
    public $stubAffix;

    /**
     * Get the relation name.
     *
     * @return string
     */
    public static function getName(): string
    {
        return Str::before(class_basename(static::class), 'Bridge');
    }

    /**
     * Get the relation class name.
     *
     * @param bool $basename [optional]
     * Should either the full class name or just it's basename be returned. Defaults to `true`.
     * @return string
     */
    public static function getRelationClassName(bool $basename = true): string
    {
        return $basename
            ? class_basename(static::$relationClassName)
            : static::$relationClassName;
    }

    /**
     * Return boolean value whether the relationship instance is 
     * a Collection of related Models or a single Model.
     *
     * @return bool
     */
    public static function returnsCollection(): bool
    {
        return static::$returnsCollection ?? true;
    }

    /**
     * Return the "count" (used by Str::plural primarily)
     * 1: One, 2: Many
     *
     * @return int
     */
    public static function getCount(): int
    {
        return static::$returnsCollection ? 2 : 1;
    }

    /**
     * Return the count of how many models must be defined
     * for relationship.
     *
     * @return int
     */
    public static function getModelCount(): int
    {
        return static::$modelCount ?? 1;
    }

    /**
     * Get the name basename for the stub.
     *
     * @return string
     */
    public function getStubName(): string
    {
        return Str::of('relationship')->when(
            !is_null($this->stubAffix),
            function (Stringable $str) {
                return $str->finish('.' . $this->stubAffix);
            }
        )->finish('.stub');
    }

    /**
     * Get the relation name as a Stringable object.
     *
     * @return \Illuminate\Support\Stringable
     */
    public function getNameAsStr(): Stringable
    {
        return Str::of($this->getName());
    }

    /**
     * Get the pattern used for preg match.
     *
     * @return string
     */
    public function getNamePattern()
    {
        $name = $this->getNameAsStr();
        $pattern = collect();

        if ($name->contains('Through')) {
            $pattern->push((string) $name->before('Through'));
            $pattern->push('Through');
        } else {
            $pattern->push((string) $name);
        }

        return '/' . $pattern->push(null)->implode("(\w*)") . '/';
    }

    /**
     * Return boolean value whether given name matches the relation.
     *
     * @param string $name
     * @return bool
     */
    public function matchesRelationshipName($name): bool
    {
        return preg_match($this->getNamePattern(), $name) > 0;
    }

    /**
     * Return a Collection of models parsed with the name pattern.
     *
     * @param string $name
     * @return \Illuminate\Support\Collection
     */
    public function getModelsFromName($name): Collection
    {
        preg_match($this->getNamePattern(), $name, $matches);

        return collect($matches)->except(0)->values()->filter();
    }
}