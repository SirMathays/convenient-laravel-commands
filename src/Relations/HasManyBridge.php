<?php

namespace SirMathays\Relations;

use Illuminate\Database\Eloquent\Relations\HasMany;

class HasManyBridge extends RelationBridge
{
    public static $count = 2;

    public static $class = HasMany::class;
}