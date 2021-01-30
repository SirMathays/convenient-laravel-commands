<?php

namespace SirMathays\Relations;

use Illuminate\Database\Eloquent\Relations\MorphTo;

class MorphToBridge extends RelationBridge
{
    protected $count = 1;

    protected $class = MorphTo::class;
}