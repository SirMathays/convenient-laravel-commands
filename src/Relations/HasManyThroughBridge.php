<?php

namespace SirMathays\Relations;

use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class HasManyThroughBridge extends RelationBridge
{
    protected static $relationClassName = HasManyThrough::class;

    protected static $modelCount = 2;

    public $stubAffix = 'has-through';
}