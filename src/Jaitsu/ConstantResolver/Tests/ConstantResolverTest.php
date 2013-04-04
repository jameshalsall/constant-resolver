<?php

namespace Jaitsu\ConstantResolver\Tests\ConstantResolver;

use Jaitsu\ConstantResolver\ConstantResolver;
use Jaitsu\ConstantResolver\Tests\Mock\NonUniqueValueConstants;
use Jaitsu\ConstantResolver\Tests\Mock\UniqueValueConstants;
use PHPUnit_Framework_TestCase;

/**
 * Tests for the constant resolver
 *
 * @package Jaitsu\ConstantResolver\Tests\ConstantResolver
 * @author  James Halsall <jhalsall@rippleffect.com>
 */
class ConstantResolverTest extends PHPUnit_Framework_TestCase
{
    /**
     * Tests the resolve() method
     *
     * Makes sure that unique constant values are resolved properly when using an object instance
     * in the constructor
     *
     * @return void
     */
    public function testResolveWithUniqueConstantValuesAndObjectInstance()
    {
        $object = new UniqueValueConstants();
        $resolver = new ConstantResolver($object);

        $this->assertEquals('Jaitsu\ConstantResolver\Tests\Mock\UniqueValueConstants::DUMMY_CONSTANT_STRING', $resolver->resolve('string value'));
        $this->assertEquals('Jaitsu\ConstantResolver\Tests\Mock\UniqueValueConstants::DUMMY_CONSTANT_INTEGER', $resolver->resolve(100));
    }

    /**
     * Tests the resolve() method
     *
     * Makes sure that unique constant values are resolved properly when using a class name
     * in the constructor
     *
     * @return void
     */
    public function testResolveWithUniqueConstantValuesAndClassName()
    {
        $object = new UniqueValueConstants();
        $resolver = new ConstantResolver(get_class($object));

        $this->assertEquals('Jaitsu\ConstantResolver\Tests\Mock\UniqueValueConstants::DUMMY_CONSTANT_STRING', $resolver->resolve('string value'));
        $this->assertEquals('Jaitsu\ConstantResolver\Tests\Mock\UniqueValueConstants::DUMMY_CONSTANT_INTEGER', $resolver->resolve(100));
    }

    /**
     * Tests the resolve() method
     *
     * Makes sure that non unique constant values are resolved properly when using an object instance
     *
     * @return void
     */
    public function testResolveWithNonUniqueConstantValuesAndObjectInstance()
    {
        $object = new NonUniqueValueConstants();
        $resolver = new ConstantResolver($object);

        $expectedString = 'Jaitsu\ConstantResolver\Tests\Mock\NonUniqueValueConstants::DUMMY_CONSTANT_STRING or ' .
                          'Jaitsu\ConstantResolver\Tests\Mock\NonUniqueValueConstants::DUMMY_CONSTANT_STRING_TWO';

        $expectedInteger = 'Jaitsu\ConstantResolver\Tests\Mock\NonUniqueValueConstants::DUMMY_CONSTANT_INTEGER or ' .
                           'Jaitsu\ConstantResolver\Tests\Mock\NonUniqueValueConstants::DUMMY_CONSTANT_INTEGER_TWO';

        $this->assertEquals($expectedString, $resolver->resolve('string value'));
        $this->assertEquals($expectedInteger, $resolver->resolve(100));
    }

    /**
     * Tests the resolve() method
     *
     * Makes sure that undefined values throw a range exception
     *
     * @expectedException \RangeException
     */
    public function testResolveWithInvalidConstantValueAndObjectInstance()
    {
        $object = new UniqueValueConstants();
        $resolver = new ConstantResolver($object);

        $resolver->resolve('undefined value');
    }

    /**
     * Tests the doResolve() method
     *
     * Makes sure that the resolve works for internal PHP classes
     *
     * @return void
     */
    public function testResolveWithInternalPhpClass()
    {
        $constantValue = \ZipArchive::ER_DELETED;
        $constantName = ConstantResolver::doResolve('ZipArchive', $constantValue);

        $this->assertEquals('ZipArchive::ER_DELETED', $constantName);
    }
}
