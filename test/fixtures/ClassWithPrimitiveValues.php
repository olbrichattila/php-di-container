<?php

declare(strict_types=1);

namespace Aolbrich\Test\Fixtures;

class ClassWithPrimitiveValues
{
    public function __construct(
        public readonly int $intValue,
        public readonly string $stringValue
    ) {}
}
