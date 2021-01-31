<?php

namespace SirMathays\Relations;

use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use Illuminate\Support\Collection;

abstract class RelationBridge
{
    /**
     * @var string
     */
    protected $class;

    /**
     * @var string
     */
    protected $count;

    /**
     * @var bool
     */
    protected $stubMode = 'normal';

    /**
     * Get relation name.
     *
     * @return string
     */
    public function getName(): string
    {
        return Str::before(class_basename(static::class), 'Bridge');
    }

    /**
     * Get relation class name.
     *
     * @param bool $basename
     * @return string
     */
    public function getClassName(bool $basename = true): string
    {
        return $basename
            ? class_basename($this->class)
            : $this->class;
    }

    public function getStubName(): string
    {
        if ($this->stubMode == 'simple') {
            return 'trait.stub';
        }
        
        return Str::of('relationship')->when(
            $this->stubMode == 'special',
            function (Stringable $str) {
                return $str->finish('.' . Str::kebab($this->getName()));
            }
        )->finish('.stub');
    }

    /**
     * Get relation name as string.
     *
     * @return \Illuminate\Support\Stringable
     */
    public function getNameAsStr(): Stringable
    {
        return Str::of($this->getName());
    }

    /**
     * Return count.
     *
     * @return int
     */
    public function getCount(): int
    {
        return $this->count ?? 1;
    }

    /**
     * Return boolean value whether the stub should be simple.
     *
     * @return bool
     */
    public function isSimple(): bool
    {
        return false;
    }

    /**
     * Get pattern used for preg match.
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
     * Parse the name pattern.
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