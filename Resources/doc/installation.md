# Installation

To install the Ivory Ordered Form bundle, you will need [Composer](http://getcomposer.org).  It's a PHP 5.3+ dependency 
manager which allows you to declare the dependent libraries your project needs and it will install & autoload them for 
you.

## Set up Composer

Composer comes with a simple phar file. To easily access it from anywhere on your system, you can execute:

``` bash
$ curl -s https://getcomposer.org/installer | php
$ sudo mv composer.phar /usr/local/bin/composer
```

## Download the bundle

Require the library in your `composer.json` file:

``` bash
$ composer require egeloen/ordered-form-bundle
```

## Register the bundle

Then, add the bundle in your `AppKernel`:

``` php
// app/AppKernel.php

public function registerBundles()
{
    return [
        // ...
        new Ivory\OrderedFormBundle\IvoryOrderedFormBundle(),
    ];
}
```
