<?php

namespace JamesHalsall\ConstantResolver;

use RangeException;
use RuntimeException;

/**
 * ConstantResolver.
 *
 * Provides functionality for mapping a class' constant values back to their
 * semantic names.
 *
 * @package JamesHalsall\ConstantResolver
 * @author  James Halsall <jhalsall@rippleffect.com>
 * @license MIT <http://opensource.org/licenses/MIT>
 */
class ConstantResolver
{
    const RETURN_STRING = 1;
    const RETURN_ARRAY  = 2;

    const DEFAULT_SEPARATOR = ' or ';
    const DEFAULT_RETURN_TYPE = self::RETURN_STRING;

    /**
     * Specifies the return type expected in resolver
     *
     * @var integer
     */
    protected $returnType = self::RETURN_STRING;

    /**
     * The class name to resolve constants for
     *
     * @var string
     */
    protected $className;

    /**
     * Constructor.
     *
     * @param string|object $class  An instance of, or the class name of the class to resolve constants for
     * @param boolean       $strict
     */
    public function __construct($class, $strict = false)
    {
        $this->configure($class);
    }

    /**
     * Resolves a constant value and returns its semantic name.
     *
     * Internally calls the convenience function static::doResolve()
     *
     * @param mixed   $constantValue The value of the constant to resolve
     *
     * @return string|array
     */
    public function resolve($constantValue)
    {
        return static::doResolve($this->className, $constantValue, $this->returnType);
    }

    /**
     * Set the return type
     *
     * @param integer $returnType New return type
     *
     * @return $this
     */
    public function setReturnType($returnType)
    {
        $this->returnType = $returnType;

        return $this;
    }

    /**
     * Convenience function used internally and exposed publicly.
     *
     * The idea of exposing this publicly was to make it easier for devs to rapidly look up
     * class constants without having to instantiate the object.
     *
     * If more than one result is found for that constant value, it returns them string joined by the given
     * separator. For example: "ClassName::NAME_ONE {separator} ClassName::NAME_TWO"
     *
     * @param string  $className     The name of the class to resolve constants up for
     * @param mixed   $constantValue The constant value to resolve
     * @param integer $returnType    Data type for resolver return value
     * @param string  $separator     The string to use to separate constant names when multiple values are found
     *
     * @throws RuntimeException If invalid return type is provided
     * @throws RangeException If a constant can not be found
     *
     * @return string|array
     *
     */
    public static function doResolve($className, $constantValue, $returnType = null, $separator = null)
    {
        // if no return type provided, use the default
        if (null === $returnType) {
            $returnType = self::DEFAULT_RETURN_TYPE;
        }

        // if no separator provided, use the default
        if (null === $separator && $returnType === self::RETURN_STRING) {
            $separator = self::DEFAULT_SEPARATOR;
        }

        $reflection = new \ReflectionClass($className);
        $constants = $reflection->getConstants();

        // filter out any values that aren't identical to the searched value
        $matches = array_filter(
            $constants,
            function ($item) use ($constantValue) {
                return $item === $constantValue;
            }
        );

        if (0 === count($matches)) {
            throw new RangeException(sprintf('No constant found with value %s', $constantValue));
        }

        // loop through the matches and add the fully qualified class name
        foreach ($matches as $name => $value) {
            $matches[$name] = sprintf('%s::%s', $className, $name);
        }

        switch ($returnType) {
            case self::RETURN_STRING:
                return implode($separator, $matches);
            case self::RETURN_ARRAY:
                return $matches;
            default:
                throw new RuntimeException(sprintf('Invalid return type (%s) provided in resolver', $returnType));
        }
    }

    /**
     * Sets up the resolver.
     *
     * @param string|object $class An instance of, or the class name of the class to resolve constants for
     *
     * @return void
     */
    protected function configure($class)
    {
        if (is_object($class)) {
            $this->className = get_class($class);
        } else {
            $this->className = $class;
        }
    }
}
