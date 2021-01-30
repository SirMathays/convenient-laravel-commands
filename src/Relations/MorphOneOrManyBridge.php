<?php

namespace SirMathays\Relations;

use Illuminate\Database\Eloquent\Relations\MorphOneOrMany;

class MorphOneOrManyBridge extends RelationBridge
{
    protected $count = 1;

    protected $class = MorphOneOrMany::class;
}