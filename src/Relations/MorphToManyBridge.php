<?php

namespace SirMathays\Relations;

use Illuminate\Database\Eloquent\Relations\MorphToMany;

class MorphToManyBridge extends RelationBridge
{
    protected $class = MorphToMany::class;

    public function getStubName(): string
    {
        return 'relationship.morph.stub';
    }
}
