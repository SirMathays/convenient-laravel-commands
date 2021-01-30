<?php

namespace SirMathays\Relations;

use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

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
     * Get relation name.
     *
     * @param bool $basename
     * @return string
     */
    public function getName(bool $basename = true): string
    {
        return $basename 
            ? class_basename($this->class)
            : $this->class;
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
     * Get relation name as string.
     *
     * @return \Illuminate\Support\Stringable
     */
    public function getNameAsStr(): Stringable
    {
        return Str::of($this->getName());
    }

    public function getNamespacedInstanceClass($namespacedModel): string
    {
        return collect([
            "\\$namespacedModel" => in_array($this->getCount(), [1, 3]),
            '\\' . Collection::class => $this->getCount() > 1,
        ])->filter()->keys()->implode("|");
    }

    public function isSimple(): bool
    {
        return false;
    }
}