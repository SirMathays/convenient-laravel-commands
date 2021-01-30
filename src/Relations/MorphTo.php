<?php

namespace SirMathays\Relations;

use Illuminate\Database\Eloquent\Relations\MorphTo;

class MorphToBridge extends RelationBridge
{
    public static $count = 1;

    public static $class = MorphTo::class;
}