<?php

namespace SirMathays\Relations;

use Illuminate\Database\Eloquent\Relations\HasMany;

class HasManyBridge extends RelationBridge
{
    protected $count = 2;

    protected $class = HasMany::class;
}