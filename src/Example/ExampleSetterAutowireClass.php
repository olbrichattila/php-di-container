<?php

declare(strict_types=1);

namespace Aolbrich\PhpDiContainer\Example;

/**
 * Example resolving with interface declaration and mapping
 */
class ExampleSetterAutowireClass
{
    private ExampleServiceInterface $exampleService;
    private ExampleSubService $exampleSubService;

    /**
     * @Autowire
     */
    public function setAutowire(
        ExampleServiceInterface $exampleService,
        ExampleSubService $exampleSubService
    ) {
        $this->exampleService =  $exampleService;
        $this->exampleSubService = $exampleSubService;
    }

    public function getResponse(): string
    {
        return
            $this->exampleService->getResponse() . ' / ' .
            $this->exampleSubService->getResponse() . PHP_EOL;
    }
}
