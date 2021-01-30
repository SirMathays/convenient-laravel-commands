<?php

namespace SirMathays\Relations;

use Illuminate\Database\Eloquent\Relations\HasOneOrMany;

class HasOneOrManyBridge extends RelationBridge
{
    public static $count = 3;

    public static $class = HasOneOrMany::class;
}