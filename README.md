# README

[![Build Status](https://secure.travis-ci.org/egeloen/IvoryOrderedFormBundle.png?branch=master)](http://travis-ci.org/egeloen/IvoryOrderedFormBundle)
[![Code Coverage](https://scrutinizer-ci.com/g/egeloen/IvoryOrderedFormBundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/egeloen/IvoryOrderedFormBundle/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/egeloen/IvoryOrderedFormBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/egeloen/IvoryOrderedFormBundle/?branch=master)
[![Dependency Status](https://www.versioneye.com/php/egeloen:ordered-form-bundle/badge.svg)](https://www.versioneye.com/php/egeloen:ordered-form-bundle)

[![Latest Stable Version](https://poser.pugx.org/egeloen/ordered-form-bundle/v/stable.svg)](https://packagist.org/packages/egeloen/ordered-form-bundle)
[![Latest Unstable Version](https://poser.pugx.org/egeloen/ordered-form-bundle/v/unstable.svg)](https://packagist.org/packages/egeloen/ordered-form-bundle)
[![Total Downloads](https://poser.pugx.org/egeloen/ordered-form-bundle/downloads.svg)](https://packagist.org/packages/egeloen/ordered-form-bundle)
[![License](https://poser.pugx.org/egeloen/ordered-form-bundle/license.svg)](https://packagist.org/packages/egeloen/ordered-form-bundle)

The bundle allows to order your Symfony2 form fields by adding the position option. A position can either be first,
last or an associative array describing before and/or after field.

## Documentation

### Installation

Require the bundle in your composer.json file:

``` json
{
    "require": {
        "egeloen/ordered-form-bundle": "~1.0",
    }
}
```

Register the bundle:

``` php
// app/AppKernel.php

public function registerBundles()
{
    return array(
        new Ivory\OrderedFormBundle\IvoryOrderedFormBundle(),
        // ...
    );
}
```

Install the bundle:

``` bash
$ composer update
```

### Usage

As explain above, the bundle adds a new option called `position` on all forms! You can get the full documentation
[here](https://github.com/egeloen/ivory-ordered-form/blob/master/doc/usage.md#position).

### Known limitations

Some use cases can not be handled by the bundle. They are listed
[here](https://github.com/egeloen/ivory-ordered-form/blob/master/doc/known_limitations.md).

## Testing

The bundle is fully unit tested by [PHPUnit](http://www.phpunit.de/) with a code coverage close to **100%**. To
execute the test suite, check the travis [configuration](/.travis.yml).

## Contribute

We love contributors! Ivory is an open source project. If you'd like to contribute, feel free to propose a PR!

## License

The Ivory Ordered Form Bundle is under the MIT license. For the full copyright and license information, please read the
[LICENSE](/LICENSE) file that was distributed with this source code.
