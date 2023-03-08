<?php

declare(strict_types=1);

namespace Aolbrich\PhpDiContainer\Example;

/**
 * Example resolving concrete class
 */
class ExampleService implements ExampleServiceInterface
{
    public function __construct(private readonly ExampleSubService $exampleSubService)
    {
    }

    public function getResponse(): string
    {
        return 'Sub ' . $this->exampleSubService->getResponse();
    }
}
