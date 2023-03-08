<?php

declare(strict_types=1);

namespace Aolbrich\PhpDiContainer\Example;

class ExampleSubService
{
    public function getResponse(): string
    {
        return 'Hello World';
    }
}
