<?php

namespace SirMathays\Relations;

use Illuminate\Database\Eloquent\Relations\MorphOne;

class MorphOneBridge extends RelationBridge
{
    protected $returnsCollection = false;

    protected $class = MorphOne::class;

    public function getStubName(): string
    {
        return 'relationship.morph.stub';
    }
}