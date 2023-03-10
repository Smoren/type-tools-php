<?php

namespace Smoren\TypeTools\Tests\Unit\Fixtures;

class ChildClass extends ParentClass
{
    private $c = null;

    public function __construct(int $a, int $b, int $c)
    {
        parent::__construct($a, $b);
        $this->c = $c;
    }

    public function toArray(): array
    {
        return [$this->a, $this->b, $this->c];
    }
}
