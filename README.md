# Beautiful and informative PHP Error Handler!

![License](https://img.shields.io/github/license/tregor/ErrorHandler.svg?style=flat-square)
![Total Downloads](https://img.shields.io/packagist/dt/tregor/error-handler.svg?style=flat-square)
![GitHub Version](https://img.shields.io/github/tag/tregor/ErrorHandler.svg?style=flat-square)

[![Latest Stable Version](https://poser.pugx.org/josantonius/ErrorHandler/v/stable)](https://packagist.org/packages/josantonius/ErrorHandler)
[![Latest Unstable Version](https://poser.pugx.org/josantonius/ErrorHandler/v/unstable)](https://packagist.org/packages/josantonius/ErrorHandler)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/fe730d61628249d280ecfb380a1ee3b8)](https://www.codacy.com/app/Josantonius/PHP-ErrorHandler?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Josantonius/PHP-ErrorHandler&amp;utm_campaign=Badge_Grade)
[![Travis](https://travis-ci.org/Josantonius/PHP-ErrorHandler.svg)](https://travis-ci.org/Josantonius/PHP-ErrorHandler)
[![CodeCov](https://codecov.io/gh/Josantonius/PHP-ErrorHandler/branch/master/graph/badge.svg)](https://codecov.io/gh/Josantonius/PHP-ErrorHandler)


PHP library for handling exceptions and errors.

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
