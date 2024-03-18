# Z-DI

## Contents of this file

 - Introduction
 - Requirements
 - Installation
 - Usage
   - Dependency Injection
   - Lazy loading
   - Eager loading
   - Recursion
 - Contributing
 - Maintainers
 - License

## Introduction

Z-DI is intended for educational purposes for beginners looking to learn more skills and master PHP. Very lightweight and minimalist DI ([Dependency Injection](https://en.wikipedia.org/wiki/Dependency_injection)) package with lazy loading and recursive configuration capabilities.


## Requirements

There is no requirement to install this package other than PHP. PHP >=7.2.0 is recommended. PHP 8.3 is highly recommended.


## Installation

Use the dependency manager [composer](https://getcomposer.org/download/) to install Z-DI.

```bash
composer require z-di/z-di
```

## Usage

### Dependency Injection (DI)

DI is a technique to inject a resource or a service (dependency) into a client on which it depends on to function. Client does not have to create nor to instantiate the resource or know how it is created.

Below is an example of a configuration file to create entry points for 2 services and a client. In this example, we inject the Email service into the Client. You can also try the Text service. Both services implements the same interface IMessage.

```php
<?php
// /conf/config.injection.php

/* @var $this \ZDI\Inject */

return array(
    \ZDI\tests\injection\Email::class => $this->create(\ZDI\tests\injection\Email::class, 'email'),

    \ZDI\tests\injection\Text::class => $this->create(\ZDI\tests\injection\Text::class, 'text'),

    \ZDI\tests\injection\Client::class => $this->create(\ZDI\tests\injection\Client::class, 'client')
        ->inject(\ZDI\tests\injection\Email::class),
);
```

To test the injection, run tests/injection/index.php.

```php
<?php

echo "\n";
echo "bootstrapping..\n";

/* @var $inject \ZDI\Inject */
$inject = require_once __DIR__ . '/../../conf/bootstrap.php';

echo "\n";
echo "get Client definition\n";

/* @var $client \ZDI\tests\injection\Client */
$client = \ZDI\Inject::getDefinition(\ZDI\tests\injection\Client::class);

echo "\n";
echo "send function call\n";
$client->send("a test message");
echo "\n";
```

``` shell
$ cd tests/injection
$ php index.php config=injection
bootstrapping..

get Client definition

send function call
ZDI\tests\injection\Email instantiated: email
ZDI\tests\injection\Client instantiated: client
ZDI\tests\injection\Email email sent: a test message
```


### Lazy loading

Lazy loading a resource is intended to differ loading or instantiating a resource until it is needed. This technique is for (web) applications where performance, high availability, acceleration and initialization times to be kept at a minimum are critical.

Below is an example of a configuration file to create an entry point for ClassA injection and its constructor parameters.

```php
<?php
// /conf/config.lazy.php

/* @var $this \ZDI\Inject */

return array(
    ZDI\tests\ignition\ClassA::class => $this->create(ZDI\tests\ignition\ClassA::class, 'hello'),
);
```

Run tests/lazy/index.php.

```php
<?php

echo "\n";
echo "bootstrapping..\n";

/* @var $inject \ZDI\Inject */
$inject = require_once __DIR__ . '/../../conf/bootstrap.php';

echo "\n";
echo "get ClassA definition\n";

/* @var $classA ZDI\tests\ignition\ClassA */
$classA = \ZDI\Inject::getDefinition(ZDI\tests\ignition\ClassA::class);

echo "\n";
echo "foo function call\n";
$classA->foo("bar");
echo "\n";
```

``` shell
$ cd tests/ignition
$ php index.php config=lazy
bootstrapping..

get ClassA definition

foo function call
ZDI\tests\ignition\ClassA instantiated: hello
ZDI\tests\ignition\ClassA function called: bar
```

The ClassA is instantiated **after** making the first *foo()* function call. As long as *foo()* is not called, the ClassA will never load.


### Eager loading

Alternatively, eager loading is to load the resource as soon as the bootstrap is started. In our example above, instead of just referencing the resource, it will be instantiated using *instance()* function as shown below.

```php
<?php
// /conf/config.lazy.php

/* @var $this \ZDI\Inject */

return array(
    ZDI\tests\lazy\ClassA::class => $this->instance(ZDI\tests\lazy\ClassA::class, 'hello'),
);
```

``` shell
$ cd tests/ignition
$ php index.php config=eager
bootstrapping..
ZDI\tests\ignition\ClassA instantiated: hello

get ClassA definition

foo function call
ZDI\tests\ignition\ClassA function called: bar
```

The ClassA is instantiated **before** making the first *foo()* function call.

**NOTE:** In our injection example above we have used lazy loading, if we rather would like to use eager loading, we need to inject the instantiated service when instantiating the client.

```php
<?php

/* @var $this \ZDI\Inject */

return array(
    \ZDI\tests\injection\Email::class => $this->instance(\ZDI\tests\injection\Email::class, 'email'),

    \ZDI\tests\injection\Text::class => $this->instance(\ZDI\tests\injection\Text::class, 'text'),

    \ZDI\tests\injection\Client::class => $this->instance(
            \ZDI\tests\injection\Client::class, $this->instance(\ZDI\tests\injection\Email::class, 'text'), 
            'client'
        ),
);
```


### Recursion

Injection can be replaced recursively based on the environment variable such as staging or production, the hostname or any other specific configuration. The first element in the array is the default injections configuration file. 

``` php
<?php

namespace ZDI\conf;

require_once __DIR__ . '/../vendor/autoload.php';

parse_str($argv[1], $arg);
$config = $arg['config'] ?? '';

return (new \ZDI\Inject(\ZDI\Magic::class))
        ->setDefinitions(
            [
                __DIR__ . '/config.php',
                __DIR__ . "/config.$config.php",
                __DIR__ . '/config.' . strtolower(gethostname()) . '.php'
            ]
        );

```

To test the recursion..

#### Default configuration

```php
[
    ZDI\tests\ignition\ClassA::class => $this->create(ZDI\tests\ignition\ClassA::class, 'foo', 'bar'),
]
```

```shell
$ cd tests/ignition
$ php index.php
bootstrapping..

get ClassA definition

foo function call
ZDI\tests\ignition\ClassA instantiated: foo
ZDI\tests\ignition\ClassA function called: bar
```

#### Dev configuration

```php
[
    ZDI\tests\ignition\ClassA::class => $this->create(ZDI\tests\ignition\ClassB::class, 'foo', 'bar'),
]
```

```shell
$ cd tests/ignition
$ php index.php config=dev
bootstrapping..

get ClassA definition

foo function call
ZDI\tests\ignition\ClassB instantiated: foo
ZDI\tests\ignition\ClassB function called: bar
```

#### Production configuration

```php
[
    ZDI\tests\ignition\ClassA::class => $this->create(ZDI\tests\ignition\ClassC::class, 'foo 1', 'bar 2'),
]
```

```shell
$ cd tests/ignition
$ php index.php config=prod
bootstrapping..

get ClassA definition

foo function call
ZDI\tests\ignition\ClassC instantiated: foo 1
ZDI\tests\ignition\ClassC function called: bar
```


## Contributing

Pull requests are welcome. For major changes, please open an issue first
to discuss what you would like to change.

Please make sure to update tests as appropriate.


## Maintainers

- [Hatem Azzouzi](https://www.elasticweb.link/#contact)


## License

[MIT](https://choosealicense.com/licenses/mit/)