<?php

namespace SirMathays\Relations;

use Illuminate\Database\Eloquent\Relations\MorphToMany;

class MorphToManyBridge extends RelationBridge
{
    public static $count = 2;

    public static $class = MorphToMany::class;
}
