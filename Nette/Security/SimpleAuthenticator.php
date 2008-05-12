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


require_once dirname(__FILE__) . '/../Security/IAuthenticator.php';

require_once dirname(__FILE__) . '/../Object.php';

require_once dirname(__FILE__) . '/../Security/Identity.php';

require_once dirname(__FILE__) . '/../Security/AuthenticationException.php';



/**
 * Trivial implementation of IAuthenticator.
 *
 * @author     David Grudl
 * @copyright  Copyright (c) 2004, 2008 David Grudl
 * @package    Nette::Security
 * @version    $Revision$ $Date$
 */
class SimpleAuthenticator extends /*Nette::*/Object implements IAuthenticator
{
	/** @var array */
	private $userlist;


	/**
	 * @param  array  list of usernames and passwords
	 */
	public function __construct(array $userlist)
	{
		$this->userlist = $userlist;
	}



	/**
	 * Performs an authentication against e.g. database.
	 * and returns IIdentity on success or throws AuthenticationException
	 *
	 * @param  array
	 * @return IIdentity
	 * @throws AuthenticationException
	 */
	public function authenticate(array $credentials)
	{
		$username = $credentials['username'];
		foreach ($this->userlist as $name => $pass) {
			if (strcasecmp($name, $credentials['username']) === 0) {
				if (strcasecmp($pass, $credentials['password']) === 0) {
					// matched!
					return new Identity($name);
				}

				throw new AuthenticationException('Invalid password', AuthenticationException::INVALID_CREDENTIAL);
			}
		}

		throw new AuthenticationException('User not found', AuthenticationException::IDENTITY_NOT_FOUND);
	}

}
