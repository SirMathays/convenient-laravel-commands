<?php

namespace SirMathays\Convenience\Relations;

use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class HasManyThroughBridge extends RelationBridge
{
    protected static $relationClassName = HasManyThrough::class;

    protected static $modelCount = 2;

    public static $stubGroupAffix = 'has-through';
}