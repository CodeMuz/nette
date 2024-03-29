<?php

/**
 * Nette Framework
 *
 * @copyright  Copyright (c) 2004, 2010 David Grudl
 * @license    http://nettephp.com/license  Nette license
 * @link       http://nettephp.com
 * @category   Nette
 * @package    Nette
 */

/*namespace Nette;*/



/**
 * Nette\Object behaviour mixin.
 *
 * @copyright  Copyright (c) 2004, 2010 David Grudl
 * @package    Nette
 */
final class ObjectMixin
{
	/** @var array */
	private static $methods;



	/**
	 * Static class - cannot be instantiated.
	 */
	final public function __construct()
	{
		throw new /*\*/LogicException("Cannot instantiate static class " . get_class($this));
	}



	/**
	 * Call to undefined method.
	 *
	 * @param  string  method name
	 * @param  array   arguments
	 * @return mixed
	 * @throws \MemberAccessException
	 */
	public static function call($_this, $name, $args)
	{
		$class = new /*Nette\Reflection\*/ClassReflection($_this);

		if ($name === '') {
			throw new /*\*/MemberAccessException("Call to class '$class->name' method without name.");
		}

		// event functionality
		if ($class->hasEventProperty($name)) {
			if (is_array($list = $_this->$name) || $list instanceof /*\*/Traversable) {
				foreach ($list as $handler) {
					callback($handler)->invokeArgs($args);
				}
			}
			return NULL;
		}

		// extension methods
		if ($cb = $class->getExtensionMethod($name)) {
			array_unshift($args, $_this);
			return $cb->invokeArgs($args);
		}

		throw new /*\*/MemberAccessException("Call to undefined method $class->name::$name().");
	}



	/**
	 * Returns property value.
	 *
	 * @param  string  property name
	 * @return mixed   property value
	 * @throws \MemberAccessException if the property is not defined.
	 */
	public static function & get($_this, $name)
	{
		$class = get_class($_this);

		if ($name === '') {
			throw new /*\*/MemberAccessException("Cannot read a class '$class' property without name.");
		}

		if (!isset(self::$methods[$class])) {
			// get_class_methods returns ONLY PUBLIC methods of objects
			// but returns static methods too (nothing doing...)
			// and is much faster than reflection
			// (works good since 5.0.4)
			self::$methods[$class] = array_flip(get_class_methods($class));
		}

		// property getter support
		$name[0] = $name[0] & "\xDF"; // case-sensitive checking, capitalize first character
		$m = 'get' . $name;
		if (isset(self::$methods[$class][$m])) {
			// ampersands:
			// - uses &__get() because declaration should be forward compatible (e.g. with Nette\Web\Html)
			// - doesn't call &$_this->$m because user could bypass property setter by: $x = & $obj->property; $x = 'new value';
			$val = $_this->$m();
			return $val;
		}

		$m = 'is' . $name;
		if (isset(self::$methods[$class][$m])) {
			$val = $_this->$m();
			return $val;
		}

		$name = func_get_arg(1);
		throw new /*\*/MemberAccessException("Cannot read an undeclared property $class::\$$name.");
	}



	/**
	 * Sets value of a property.
	 *
	 * @param  string  property name
	 * @param  mixed   property value
	 * @return void
	 * @throws \MemberAccessException if the property is not defined or is read-only
	 */
	public static function set($_this, $name, $value)
	{
		$class = get_class($_this);

		if ($name === '') {
			throw new /*\*/MemberAccessException("Cannot assign to a class '$class' property without name.");
		}

		if (!isset(self::$methods[$class])) {
			self::$methods[$class] = array_flip(get_class_methods($class));
		}

		// property setter support
		$name[0] = $name[0] & "\xDF"; // case-sensitive checking, capitalize first character
		if (isset(self::$methods[$class]['get' . $name]) || isset(self::$methods[$class]['is' . $name])) {
			$m = 'set' . $name;
			if (isset(self::$methods[$class][$m])) {
				$_this->$m($value);
				return;

			} else {
				$name = func_get_arg(1);
				throw new /*\*/MemberAccessException("Cannot assign to a read-only property $class::\$$name.");
			}
		}

		$name = func_get_arg(1);
		throw new /*\*/MemberAccessException("Cannot assign to an undeclared property $class::\$$name.");
	}



	/**
	 * Is property defined?
	 *
	 * @param  string  property name
	 * @return bool
	 */
	public static function has($_this, $name)
	{
		if ($name === '') {
			return FALSE;
		}

		$class = get_class($_this);
		if (!isset(self::$methods[$class])) {
			self::$methods[$class] = array_flip(get_class_methods($class));
		}

		$name[0] = $name[0] & "\xDF";
		return isset(self::$methods[$class]['get' . $name]) || isset(self::$methods[$class]['is' . $name]);
	}

}
