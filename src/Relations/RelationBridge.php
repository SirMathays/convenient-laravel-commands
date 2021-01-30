<?php

namespace SirMathays\Relations;

use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

abstract class RelationBridge
{
    /**
     * @var string
     */
    public static $class;

    /**
     * @var string
     */
    public static $count;

    /**
     * Get relation name.
     *
     * @return string
     */
    public function getName(): string
    {
        return class_basename($this->class);
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
            "\\$namespacedModel" => in_array($this->count, [1, 3]),
            '\\' . Collection::class => $this->count > 1,
        ])->filter()->keys()->implode("|");
    }

    public function isSimple(): bool
    {
        return false;
    }
}