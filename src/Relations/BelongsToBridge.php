<?php

namespace SirMathays\Relations;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BelongsToBridge extends RelationBridge
{
    protected $count = 1;

    protected $class = BelongsTo::class;
}