<?php

namespace SirMathays\Relations;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BelongsToManyBridge extends RelationBridge
{
    public static $count = 2;

    public static $class = BelongsToMany::class;
}