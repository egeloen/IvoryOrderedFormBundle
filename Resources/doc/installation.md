# Installation

Require the bundle in your composer.json file:

``` json
{
    "require": {
        "egeloen/ordered-form-bundle": "1.*",
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
