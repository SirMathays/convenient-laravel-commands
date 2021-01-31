<?php

namespace SirMathays\Relations;

use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class HasManyThroughBridge extends RelationBridge
{
    protected $count = 2;

    protected $class = HasManyThrough::class;

    protected $stubMode = 'special';
}