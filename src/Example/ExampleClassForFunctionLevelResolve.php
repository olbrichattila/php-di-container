<?php

declare(strict_types=1);

namespace Aolbrich\PhpDiContainer\Example;

/**
 * Example resolving with interface declaration and mapping
 */
class ExampleClassForFunctionLevelResolve
{
    /**
     * @Autowire
     */
    public function getResponseWithAutowiredParams(
        ExampleServiceInterface $exampleService,
        ExampleSubService $exampleSubService): string
    {
        return
            $exampleService->getResponse() . ' / ' .
            $exampleSubService->getResponse() . PHP_EOL;
    }
}

