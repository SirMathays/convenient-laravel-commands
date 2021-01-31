<?php

namespace SirMathays\Relations;

use Illuminate\Database\Eloquent\Relations\MorphOne;

class MorphOneBridge extends RelationBridge
{
    protected static $relationClassName = MorphOne::class;

    protected static $returnsCollection = false;

    public $stubAffix = 'morph';
}