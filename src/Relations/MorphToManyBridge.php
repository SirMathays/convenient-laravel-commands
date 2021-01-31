<?php

namespace SirMathays\Relations;

use Illuminate\Database\Eloquent\Relations\MorphToMany;

class MorphToManyBridge extends RelationBridge
{
    protected $count = 2;

    protected $class = MorphToMany::class;

    public function getStubName(): string
    {
        return 'relationship.morph.stub';
    }
}
