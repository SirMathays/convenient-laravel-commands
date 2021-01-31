<?php

namespace SirMathays\Relations;

use Illuminate\Database\Eloquent\Relations\MorphMany;

class MorphManyBridge extends RelationBridge
{
    protected static $relationClassName = MorphMany::class;

    public $stubAffix = 'morph';
}