<?php

namespace SirMathays\Relations;

use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class HasOneThroughBridge extends RelationBridge
{
    protected $returnsCollection = false;

    protected $class = HasOneThrough::class;

    protected $modelCount = 2;
}