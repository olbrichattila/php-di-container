<?php

declare(strict_types=1);

namespace Aolbrich\PhpDiContainer\Example;

/**
 * Example resoling with interface declaration and mapping
 */
class ExampleClass
{
    public function __construct(
        private readonly ExampleServiceInterface $exampleService,
        private readonly ExampleSubService $exampleSubService,
    ) {
    }

    public function getResponse(): string
    {
        return
            $this->exampleService->getResponse() . ' / ' .
            $this->exampleSubService->getResponse() . PHP_EOL;
    }
}
