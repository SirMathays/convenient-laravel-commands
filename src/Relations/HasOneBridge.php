<?php

namespace SirMathays\Relations;

use Illuminate\Database\Eloquent\Relations\HasOne;

class HasOneBridge extends RelationBridge
{
    protected $returnsCollection = false;

    protected $class = HasOne::class;
}