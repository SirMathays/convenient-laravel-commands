<?php

namespace SirMathays\Relations;

use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class HasManyThroughBridge extends RelationBridge
{
    protected $class = HasManyThrough::class;

    protected $stubMode = 'special';

    protected $modelCount = 2;
}