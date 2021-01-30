<?php

namespace SirMathays\Relations;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BelongsToManyBridge extends RelationBridge
{
    protected $count = 2;

    protected $class = BelongsToMany::class;
}