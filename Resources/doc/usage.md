# Usage

When you register the bundle in your application kernel, some internal classes of the form component are replaced by
new one which will allow us to order the form fields. There is no hack, just replace some factories :)

Now let's go for explanations!

## Position

As explain above, the bundle adds a new option called `position` on all forms, we will explain how you can configure it!

### First position

The first position allows you to place the form at the first position :)

``` php
$builder
    ->add('b', 'text')
    ->add('a', 'text', array('position' => 'first'))
    ->add('c', 'text');
```

The output will be: A => B => C.

Additionally, here, we see an other interesting thing: **The orderer maintains orders if there is no position**.

The same goes if you use multiple first:

``` php
$builder
    ->add('c', 'text')
    ->add('a', 'text', array('position' => 'first'))
    ->add('b', 'text', array('position' => 'first'));
```

The output will be: A => B => C.

### Last position

The last position allows you to place the form at the last position...

``` php
$builder
    ->add('c', 'text', array('position' => 'last'))
    ->add('a', 'text')
    ->add('b', 'text');
```

The output will be: A => B => C.

If you can use multiple last:

``` php
$builder
    ->add('b', 'text', array('position' => 'last'))
    ->add('a', 'text')
    ->add('c', 'text', array('position' => 'last'));
```

The output will be: A => B => C.

### Before position

The before position allows you to place the form just before an other form :)

``` php
$builder
    ->add('b', 'text')
    ->add('a', 'text', array('position' => array('before' => 'b')))
    ->add('c', 'text');
```

The output will be: A => B => C.

If you can use multiple before:

``` php
$builder
    ->add('a', 'text', array('position' => array('before' => 'b')))
    ->add('c', 'text')
    ->add('b', 'text', array('position' => array('before' => 'c')));
```

The output will be: A => B => C.

### After position

The after position allows you to place the form just after an other form...

``` php
$builder
    ->add('b', 'text', array('position' => array('after' => 'a')))
    ->add('a', 'text')
    ->add('c', 'text');
```

The output will be: A => B => C.

If you can use multiple after:

``` php
$builder
    ->add('a', 'text')
    ->add('c', 'text', array('position' => array('after' => 'b')))
    ->add('b', 'text', array('position' => array('after' => 'a')));
```

The output will be: A => B => C.

### Mixed options

You can obviously mix first, last, before & after together to archive more complex use cases:

``` php
$builder
    ->add('g', 'text', array('position' => 'last'))
    ->add('a', 'text', array('position' => 'first'))
    ->add('c', 'text')
    ->add('f', 'text')
    ->add('e', 'text', array('position' => array('before' => 'f')))
    ->add('d', 'text', array('position' => array('after' => 'c')))
    ->add('b', 'text', array('position' => 'first'));
```

The output will be: A => B => C => D => E => F => G.

Enjoy!
