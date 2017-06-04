Jaxon Library for Zend Framework
================================

This package integrates the [Jaxon library](https://github.com/jaxon-php/jaxon-core) with the Zend Framework 2.3+ and 3.

Features
--------

- Automatically register Jaxon classes from a preset directory.
- Read Jaxon options from a config file.

Installation
------------

Add the following lines in the `composer.json` file, and run the `composer update` command.
```json
"require": {
    "jaxon-php/jaxon-zend": "~2.0"
}
```

Add the Jaxon module to the `modules` entry in the `config/application.config.php` or `config/modules.config.php` config file.
```php
    'modules' => array(
        'Application',
        'Jaxon\Zend',
    ),
```

### Zend Framework 2

Edit the module/Application/config/module.config.php as follow.

 1. Import the Jaxon classes into the current namespace

```php
use Jaxon\Zend\Factory\Zf2ControllerFactory;
```

 2. Register the Jaxon plugin with the Service Manager

```php
    'service_manager' => array(
        'invokables' => array(
            'JaxonPlugin' => 'Jaxon\Zend\Controller\Plugin\JaxonPlugin',
        ),
    ),
```

 3. Use the provided factory to create both the application controller and the Jaxon ZF controller.

```php
    'controllers' => array(
        'factories' => array(
            'Application\Controller\Demo' => Zf2ControllerFactory::class,
            'Jaxon\Zend\Controller\Jaxon' => Zf2ControllerFactory::class,
        ),
    ),
```

This factory injects the Jaxon plugin into the ZF controller constructor.

 4. Route the Jaxon request URI to the Jaxon Controller

```php
    'router' => array(
        'routes' => array(
            // Route to the Jaxon request processor
            'jaxon' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/jaxon',
                    'defaults' => array(
                        'controller' => 'Jaxon\Zend\Controller\Jaxon',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
```

### Zend Framework 3

Edit the module/Application/config/module.config.php file as follow.

1. Import the Jaxon classes into the current namespace

```php
use Jaxon\Zend\Factory\Zf3ControllerFactory;
use Jaxon\Zend\Controller\Plugin\JaxonPlugin;
use Jaxon\Zend\Controller\JaxonController;
```

2. Register the Jaxon plugin with the Service Manager

```php
    'service_manager' => array(
        'invokables' => array(
            'JaxonPlugin' => JaxonPlugin::class,
        ),
    ),
```
Or
```php
    'service_manager' => array(
        'factories' => array(
            JaxonPlugin::class => InvokableFactory::class,
        ),
        'aliases' => array(
            'JaxonPlugin' => JaxonPlugin::class,
        ),
    ),
```

3. Use the provided factory to create both the application controller and the Jaxon ZF controller.

```php
    'controllers' => [
        'factories' => [
            Controller\DemoController::class => Zf3ControllerFactory::class,
            JaxonController::class => Zf3ControllerFactory::class,
        ],
    ],
```

This factory injects the Jaxon plugin into the ZF controller constructor.

4. Route the Jaxon request URI to the Jaxon Controller

```php
    'router' => [
        'routes' => [
            // Route to the Jaxon request processor
            'jaxon' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/jaxon',
                    'defaults' => [
                        'controller' => JaxonController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
```

Configuration
-------------

The config of the Jaxon library is defined in the `config/jaxon.config.php` file.
A sample config file is provided in [this repo](github.com/jaxon-php/jaxon-zend/blob/master/config/module.config.php).

The settings in the `config/jaxon.config.php` config file are separated into two sections.
The options in the `lib` section are those of the Jaxon core library, while the options in the `app` sections are those of the Zend Framework application.

The following options can be defined in the `app` section of the config file.

| Name | Description |
|------|---------------|
| classes | An array of directory containing Jaxon application classes |
| views   | An array of directory containing Jaxon application views |
| | | |

By default, the `views` array is empty. Views are rendered from the framework default location.
There's a single entry in the `classes` array with the following values.

| Name      | Default value   | Description |
|-----------|-----------------|-------------|
| directory | {app_dir}/jaxon/Classes | The directory of the Jaxon classes |
| namespace | \Jaxon\App      | The namespace of the Jaxon classes |
| separator | .               | The separator in Jaxon class names |
| protected | empty array     | Prevent Jaxon from exporting some methods |
| | | |

Usage
-----

This is an example of a Zend Framework controller using the Jaxon library.
```php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Jaxon\Zend\Controller\Plugin\JaxonPlugin;

class DemoController extends AbstractActionController
{
    /**
     * @var \Jaxon\Zend\Controller\Plugin\JaxonPlugin
     */
    protected $jaxon;

    public function __construct(JaxonPlugin $jaxon)
    {
        $this->jaxon = $jaxon;
    }

    public function indexAction()
    {
        // Call the Jaxon module
        $this->jaxon->register();

        $view = new ViewModel(array(
            'jaxonCss' => $this->jaxon->css(),
            'jaxonJs' => $this->jaxon->js(),
            'jaxonScript' => $this->jaxon->script(),
        ));
        $view->setTemplate('demo/index');
        return $view;
    }
}
```

Before it prints the page, the controller makes a call to `$jaxon->register()` to export the Jaxon classes.
Then it calls the `$jaxon->css()`, `$jaxon->js()` and `$jaxon->script()` functions to get the CSS and javascript codes generated by Jaxon, which it inserts in the page.

### The Jaxon classes

The Jaxon classes must inherit from `\Jaxon\Sentry\Classes\Armada`.
By default, they are loaded from the `jaxon/Classes` dir at the root of the Zend Framework application, and the associated namespace is `\Jaxon\App`.

This is a simple example of a Jaxon class, defined in the `jaxon/Classes/HelloWorld.php` file.

```php
namespace Jaxon\App;

class HelloWorld extends \Jaxon\Sentry\Classes\Armada
{
    public function sayHello()
    {
        $this->response->assign('div2', 'innerHTML', 'Hello World!');
        return $this->response;
    }
}
```

Check the [jaxon-examples](https://github.com/jaxon-php/jaxon-examples/tree/master/frameworks/zend) package for more examples.

Contribute
----------

- Issue Tracker: github.com/jaxon-php/jaxon-zend/issues
- Source Code: github.com/jaxon-php/jaxon-zend

License
-------

The package is licensed under the BSD license.
