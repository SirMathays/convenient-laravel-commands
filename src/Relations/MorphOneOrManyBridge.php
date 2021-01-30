<?php

namespace SirMathays\Relations;

use Illuminate\Database\Eloquent\Relations\MorphOneOrMany;

class MorphOneOrManyBridge extends RelationBridge
{
    public static $count = 1;

    public static $class = MorphOneOrMany::class;
}