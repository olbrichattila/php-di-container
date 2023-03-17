# Constructor Dependency Injection container
## This class implements a simple constructor dependency injection container
## Installation:

```
composer require aolbrich/php-di-container
```

### Basic Usage:
If the class constructor implements a callable class then it will be auto wired, no mapping required. For example:

```
class ExampleClass
{
    public function __construct(private readonly ExampleClass $exampleClass) {}

    ...    
}
```
### Implementation:
```
use Aolbrich\PhpDiContainer\Container;

// Create new container
$container = new Container();

// Resolve class
$class = $container->get(ExampleClass::class);
```
## Returns new class and autowire all methods where the annotation says @atowire
(Note: The new class will not return with the same class name, or not even inherited from the original class)
```
$container = new Container();
$container->set(ExampleServiceInterface::class, ExampleService::class);

$class = $container->resolveClass(ExampleClassForFunctionLevelResolve::class);

echo $class->getResponseWithAutowiredParams(); // they will be auto wired
```
## Using array to set the resolver definitions
It is also possible to pass the definitions to the class via it's constructor, or calling the setDefinitions method instad of using the `set` method.
It makes possible to set multiple definitions at one:

Example:
```
$definitions = [
    ExampleServiceInterface::class => ExampleService::class,
];

$container = new Container($definitions);

```
OR
```
$definitions = [
    ExampleServiceInterface::class => ExampleService::class,
];

$container = new Container();
$container->setDefinitions($definitions);

```
## The class:
```
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

```
### Usage with interface resolution:
If the class implements a constuctor injection with interface type hint, then the container cannot resolve the depenceny automatically, therefore mapping should be provided by the set method. Mapping can be done by interface and class names, or interface name and closure as well.

The following two example illustrate those solutions:
```
class ExampleClass
{
    public function __construct(private readonly ExampleInterface $example) {}

    ...    
}
```
### Implementation:
```
use Aolbrich\PhpDiContainer\Container;

// Create new container
$container = new Container();

// Configure what to resolve
$container->set(
    ExampleInterface::class,
    ExampleClass::class
);
$class = $container->get(ExampleClass::class);
```
### Same implementation using closure:
```
use Aolbrich\PhpDiContainer\Container;

// Create new container
$container = new Container();

// Configure what to resolve
$container->set(ExampleServiceInterface::class, function(Container $container) {
    return $container->get(ExampleService::class);
});

$class = $container->get(ExampleClass::class);
```
## Setter Autowire
It is also possible to auto wire setters, The requirement for auto wiring setters are:
- The method have to be public
- The method name have to start with "set" like "setLogger()"
- To auto wire, you need to add the @autowire annotations.

There can be any amount of setters

Example class wiring two dependencies at one setter.

```
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
```
### Run the unit test
```
./vendor/bin/phpunit test
```

## Tools

Run code quality check:
```
./vendor/bin/phpstan analyse src test
./vendor/bin/psalm --show-info=true
```

## Note

This is not a full implementation of a dependency injection container. It resolves only constructor dependencies.

Features missing / will be added
- Only basic circular reference check added, improve to check for example, if ClassA depends on ClassB, and ClassB depends on ClassC, which in turn depends on ClassA, the code would not detect this circular reference. This can be addressed by maintaining a stack of dependencies and checking for cycles in the stack.
- ~~Constructor Injection~~
- ~~Setter Injection~~
- ~~Method Injection~~
- Caching
- Aliases
- Injecting primitive paramater values
- Singleton support,
- Optional parameters
- Annotations
- Immutable-setter Injection
- Property Injection

## Licence
MIT licence
