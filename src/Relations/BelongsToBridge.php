<?php

namespace SirMathays\Relations;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BelongsToBridge extends RelationBridge
{
    protected $returnsCollection = false;

    protected $class = BelongsTo::class;

    protected $stubMode = 'special';
}