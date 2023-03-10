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
- Caching
- Aliases
- Injecting primitive paramater values
- Singleton support,
- Optional parameters
- Annotations
- ~~Constructor Injection (added)~~
- Immutable-setter Injection
- Setter Injection
- Property Injection

## Licence
MIT licence
