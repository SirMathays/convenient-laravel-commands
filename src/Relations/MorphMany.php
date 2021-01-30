<?php

namespace SirMathays\Relations;

use Illuminate\Database\Eloquent\Relations\MorphMany;

class MorphManyBridge extends RelationBridge
{
    public static $count = 2;

    public static $class = MorphMany::class;
}