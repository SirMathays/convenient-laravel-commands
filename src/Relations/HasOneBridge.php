<?php

namespace SirMathays\Relations;

use Illuminate\Database\Eloquent\Relations\HasOne;

class HasOneBridge extends RelationBridge
{
    protected $count = 1;

    protected $class = HasOne::class;
}