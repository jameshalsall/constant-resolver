<?php

namespace JamesHalsall\ConstantResolver\Tests\Mock;

/**
 * Dummy constants class.
 *
 * A class with constant values that do not have unique values
 *
 * @package JamesHalsall\ConstantResolver\Tests\ConstantResolver\Mock
 * @author  James Halsall <jhalsall@rippleffect.com>
 */
class NonUniqueValueConstants
{
    const DUMMY_CONSTANT_STRING = 'string value';
    const DUMMY_CONSTANT_STRING_TWO = 'string value';
    const DUMMY_CONSTANT_INTEGER = 100;
    const DUMMY_CONSTANT_INTEGER_TWO = 100;
}
