<?php

namespace SirMathays\Relations;

use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class HasOneThroughBridge extends RelationBridge
{
    protected $count = 1;

    protected $class = HasOneThrough::class;
}