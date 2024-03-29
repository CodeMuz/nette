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
 * The Nette Framework.
 *
 * @copyright  Copyright (c) 2004, 2010 David Grudl
 * @package    Nette
 */
final class Framework
{

	/**#@+ Nette Framework version identification */
	const NAME = 'Nette Framework';

	const VERSION = '0.9.3';

	const REVISION = 'cc750ea released on 2010-01-28';

	const PACKAGE = '';
	/**#@-*/



	/**
	 * Static class - cannot be instantiated.
	 */
	final public function __construct()
	{
		throw new /*\*/LogicException("Cannot instantiate static class " . get_class($this));
	}



	/**
	 * Compares current Nette Framework version with given version.
	 * @param  string
	 * @return int
	 */
	public static function compareVersion($version)
	{
		return version_compare($version, self::VERSION);
	}



	/**
	 * Nette Framework promotion.
	 * @return void
	 */
	public static function promo($xhtml = TRUE)
	{
		echo '<a href="http://nettephp.com/" title="Nette Framework - The Most Innovative PHP Framework"><img ',
			'src="http://nettephp.com/images/nette-powered.gif" alt="Powered by Nette Framework" width="80" height="15"',
			($xhtml ? ' />' : '>'), '</a>';
	}


	/**/
	/**
	 * Fixes namespaced class/interface in PHP < 5.3
	 */
	public static function fixNamespace(& $class)
	{
		if ($a = strrpos($class, '\\')) {
			$class = substr($class, $a + 1);
		}
	}
	/**/

}
