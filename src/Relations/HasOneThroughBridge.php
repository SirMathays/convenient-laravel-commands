<?php

namespace SirMathays\Relations;

use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class HasOneThroughBridge extends RelationBridge
{
    public static $count = 1;

    public static $class = HasOneThrough::class;
}