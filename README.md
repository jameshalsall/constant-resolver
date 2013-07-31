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

````

```` php
<?php

use JamesHalsall\ConstantResolver;

$someClass = new SomeClass();
$resolver  = new ConstantResolver($someClass);

// returns 'SomeClass::MY_CONSTANT_NAME'
$constant = $resolver->resolve(1);
````

## Example

```` php
<?php

$httpErrorCodes = new HttpErrorCodes();

$resolver = new ConstantResolver($enumerableClass);

// returns 'HttpErrorCodes::NOT_FOUND'
$resolver->resolve(404);
````

Same example with array return:

```` php
<?php

...

$resolver->setReturnType(ConstantResolver::RETURN_ARRAY);

/**
 * returns array(
 *     'NOT_FOUND' => 'HttpErrorCodes::NOT_FOUND'
 * );
 */
````