<?php

/**
 * Nette Framework
 *
 * Copyright (c) 2004, 2008 David Grudl (http://davidgrudl.com)
 *
 * This source file is subject to the "Nette license" that is bundled
 * with this package in the file license.txt.
 *
 * For more information please see http://nettephp.com/
 *
 * @copyright  Copyright (c) 2004, 2008 David Grudl
 * @license    http://nettephp.com/license  Nette license
 * @link       http://nettephp.com/
 * @category   Nette
 * @package    Nette::Security
 */

/*namespace Nette::Security;*/



/**
 * Authentication exception.
 *
 * @author     David Grudl
 * @copyright  Copyright (c) 2004, 2008 David Grudl
 * @package    Nette::Security
 * @version    $Revision$ $Date$
 */
class AuthenticationException extends /*::*/Exception
{

	/**
	 * Exception error codes.
	 */
	const IDENTITY_NOT_FOUND = 1;

	const INVALID_CREDENTIAL = 2;

}