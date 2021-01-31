<?php

namespace SirMathays\Relations;

use Illuminate\Database\Eloquent\Relations\MorphMany;

class MorphManyBridge extends RelationBridge
{
    protected $count = 2;

    protected $class = MorphMany::class;

    public function getStubName(): string
    {
        return 'relationship.morph.stub';
    }
}