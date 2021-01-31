<?php

namespace SirMathays\Relations;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BelongsToManyBridge extends RelationBridge
{
    protected static $relationClassName = BelongsToMany::class;
}