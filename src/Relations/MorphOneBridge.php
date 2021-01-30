<?php

namespace SirMathays\Relations;

use Illuminate\Database\Eloquent\Relations\MorphOne;

class MorphOneBridge extends RelationBridge
{
    public static $count = 1;

    public static $class = MorphOne::class;
}