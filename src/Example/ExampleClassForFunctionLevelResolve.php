<?php

declare(strict_types=1);

namespace Aolbrich\PhpDiContainer\Example;

/**
 * Example resolving with interface declaration and mapping
 */
class ExampleClassForFunctionLevelResolve
{
    public function __Construct(
        private readonly ExampleServiceInterface $exampleService,
    ) {
    }

    /**
     * @Autowire
     */
    public function getResponseWithAutowiredParams(
        ExampleServiceInterface $exampleService,
        ExampleSubService $exampleSubService): string
    {
        return
            $this->exampleService->getResponse() . ' / ' .
            $exampleService->getResponse() . ' / ' .
            $exampleSubService->getResponse() . PHP_EOL;
    }
}
