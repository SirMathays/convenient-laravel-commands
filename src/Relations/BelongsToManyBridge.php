<?php

namespace SirMathays\Relations;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BelongsToManyBridge extends RelationBridge
{
    protected $class = BelongsToMany::class;
}