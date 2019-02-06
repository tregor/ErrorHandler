# Beautiful and informative PHP Error Handler!

[![Total Downloads](https://img.shields.io/packagist/dt/tregor/error-handler.svg?style=flat-square)](https://packagist.org/packages/tregor/error-handler)
[![GitHub Version](https://img.shields.io/github/tag/tregor/ErrorHandler.svg?style=flat-square)](https://github.com/tregor/ErrorHandler)
[![Last Commit](https://img.shields.io/github/last-commit/tregor/ErrorHandler.svg?style=flat-square)](https://github.com/tregor/ErrorHandler)
[![PHP Req](https://img.shields.io/packagist/php-v/tregor/error-handler.svg?style=flat-square)](https://packagist.org/packages/tregor/error-handler)
[![License](https://img.shields.io/github/license/tregor/ErrorHandler.svg?style=flat-square)](LICENSE)


PHP library for handling exceptions and errors! (Now with direct href to StackOverflow :wink: )

---

- [Requirements](#requirements)
- [Installation](#installation)
- [Available Methods](#available-methods)
- [Quick Start](#quick-start-and-usage)
- [Images](#images)
- [TODO](#todo)
- [Contribute](#contribute)
- [License](#license)
- [Copyright](#copyright)

---

## Requirements

This library is supported by **PHP versions 5.4** or higher.

## Installation

The preferred way to install this extension is through [Composer](http://getcomposer.org/download/).

To install **PHP ErrorHandler library**, simply:

    $ composer require tregor/error-handler

You can also **clone the complete repository** with Git:

    $ git clone https://github.com/treggor/ErrorHandler.git

Or **install it manually**:

[Download ErrorHandler.php](https://github.com/tregor/ErrorHandler/archive/master.zip):

    $ wget https://github.com/tregor/ErrorHandler/archive/master.zip

## Available Methods

Available methods in this library:

### - Set renderer template:

To set custom renderer template, that you can download appart or create yourself, you must provide those code:
```php
ErrorHandler::setTemplate(string $templateName);
```

### - Set trace depth:

By default trace depth is 0, equivalent to infinity. You can set trace steps depth by providing those code:
```php
ErrorHandler::setTraceDepth(integer $traceDepth);
```


## Quick Start and Usage

To use this class with **Composer**:

```php
require __DIR__ . '/vendor/autoload.php';

new tregor\ErrorHandler\ErrorHandler;
```

Or If you installed it **manually**, use it:

```php
require_once __DIR__ . '/ErrorHandler.php';

new Josantonius\ErrorHandler\ErrorHandler;
```

## Images


**EXCEPTION Handler:**
![image](img/Exception.png)

**ERROR Handler:**
![image](img/Error.png)

**WARNING Handler:**
![image](img/Notice.png)

**NOTICE Handler:**
![image](img/Warning.png)

## TODO

- [X] Make some settings.
- [ ] Make tests.
- [ ] Improve documentation.
- [ ] Refactor code.
- [ ] Make it better.
- [ ] Take a cup of coffee.

## Contribute

If you would like to help, please take a look at the list of
[issues](https://github.com/tregor/ErrorHandler/issues) or the [ToDo](#-todo) checklist.

**Pull requests**

* [Fork and clone](https://help.github.com/articles/fork-a-repo).
* Run the **tests**.
* Create a **branch**, **commit**, **push** and send me a
  [pull request](https://help.github.com/articles/using-pull-requests).

## License

This project is licensed under **MIT license**. See the [LICENSE](LICENSE) file for more info.

## Copyright

By tregor 2019

Please let me know, if you have feedback or suggestions.

You can contact me on [Facebook](https://www.facebook.com/tregor1997) or through my [email](mailto:tregor1997@gmail.com).
