<?php

namespace SirMathays\Relations;

use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class HasManyThroughBridge extends RelationBridge
{
    public static $count = 2;

    public static $class = HasManyThrough::class;
}