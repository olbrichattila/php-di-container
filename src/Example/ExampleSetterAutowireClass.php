<?php

declare(strict_types=1);

namespace Aolbrich\PhpDiContainer\Example;

/**
 * Example resolving with interface declaration and mapping
 */
class ExampleSetterAutowireClass
{
    private ExampleServiceInterface $exampleServiceSetter;
    private ExampleSubService $exampleSubServiceSetter;

    public function __Construct(
        private readonly ExampleServiceInterface $exampleService,
    ) {
    }

    /**
     * @Autowire
     */
    public function setAutowire(
        ExampleServiceInterface $exampleService,
        ExampleSubService $exampleSubService
    ) {
        $this->exampleServiceSetter =  $exampleService;
        $this->exampleSubServiceSetter = $exampleSubService;
    }

    public function getResponse(): string
    {
        return
            $this->exampleService->getResponse() . ' / ' .
            $this->exampleServiceSetter->getResponse() . ' / ' .
            $this->exampleSubServiceSetter->getResponse() . PHP_EOL;
    }
}
