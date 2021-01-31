<?php

namespace SirMathays\Relations;

use Illuminate\Database\Eloquent\Relations\HasOne;

class HasOneBridge extends RelationBridge
{
    protected static $relationClassName = HasOne::class;

    protected static $returnsCollection = false;
}