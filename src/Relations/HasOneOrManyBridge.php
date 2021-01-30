<?php

namespace SirMathays\Relations;

use Illuminate\Database\Eloquent\Relations\HasOneOrMany;

class HasOneOrManyBridge extends RelationBridge
{
    protected $count = 3;

    protected $class = HasOneOrMany::class;
}