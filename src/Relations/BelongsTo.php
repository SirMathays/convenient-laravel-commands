<?php

namespace SirMathays\Relations;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BelongsToBridge extends RelationBridge
{
    public static $count = 1;

    public static $class = BelongsTo::class;
}