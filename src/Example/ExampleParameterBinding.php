<?php

declare(strict_types=1);

namespace Aolbrich\PhpDiContainer\Example;

/**
 * Example resolving with interface declaration and mapping
 */
class ExampleParameterBinding
{
    private mixed $anyValue;

    public function __construct(
        private readonly ExampleServiceInterface $exampleService,
        private readonly ExampleSubService $exampleSubService,
        private readonly int $intValue,
        private readonly string $stringValue,
        $anyValue,
    ) {
        $this->anyValue = $anyValue;
    }

    public function getResponse(): string
    {
        return
            $this->intValue . ' / ' .
            $this->stringValue . ' / ' .
            $this->anyValue . ' / ' .
            $this->exampleService->getResponse() . ' / ' .
            $this->exampleSubService->getResponse() . PHP_EOL;
    }
}
