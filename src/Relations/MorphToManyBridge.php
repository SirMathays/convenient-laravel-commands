<?php

namespace SirMathays\Relations;

use Illuminate\Database\Eloquent\Relations\MorphToMany;

class MorphToManyBridge extends RelationBridge
{
    protected static $relationClassName = MorphToMany::class;

    public $stubAffix = 'morph';
}
