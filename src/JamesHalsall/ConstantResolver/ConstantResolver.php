<?php

namespace JamesHalsall\ConstantResolver;

use RangeException;

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
     * @param mixed $constantValue The value of the constant to resolve
     *
     * @return string
     */
    public function resolve($constantValue)
    {
        return static::doResolve($this->className, $constantValue);
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
     * @param string $className     The name of the class to resolve constants up for
     * @param mixed  $constantValue The constant value to resolve
     * @param string $separator     The string to use to separate constant names when multiple values are found
     *
     * @return string
     *
     * @throws RangeException
     */
    public static function doResolve($className, $constantValue, $separator = ' or ')
    {
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

        return implode($separator, $matches);
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
