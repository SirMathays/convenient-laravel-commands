<?php

namespace SirMathays\Relations;

use Illuminate\Database\Eloquent\Relations\HasOne;

class HasOneBridge extends RelationBridge
{
    public static $count = 1;

    public static $class = HasOne::class;
}