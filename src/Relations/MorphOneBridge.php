<?php

namespace SirMathays\Relations;

use Illuminate\Database\Eloquent\Relations\MorphOne;

class MorphOneBridge extends RelationBridge
{
    protected $count = 1;

    protected $class = MorphOne::class;

    public function getStubName(): string
    {
        return 'relationship.morph.stub';
    }
}