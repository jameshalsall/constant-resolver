# constant-resolver

PHP Class that resolves class constant values back to their semantic names.

## Installation

### Composer

Add "jameshalsall/constant-resolver" to your composer.json require section.

## Usage

Example class:
```` php
<?php

class SomeClass
{
    const MY_CONSTANT_NAME = 1;

    ...
}

```` php
<?php

use JamesHalsall\ConstantResolver;

$someClass = new SomeClass();
$resolver  = new ConstantResolver($someClass);

// returns SomeClass::MY_CONSTANT_NAME
$constant = $resolver->resolve(1);
````