<?php

/**
 * Nette Framework
 *
 * Copyright (c) 2004, 2008 David Grudl (http://davidgrudl.com)
 *
 * This source file is subject to the "Nette license" that is bundled
 * with this package in the file license.txt.
 *
 * For more information please see http://nettephp.com
 *
 * @copyright  Copyright (c) 2004, 2008 David Grudl
 * @license    http://nettephp.com/license  Nette license
 * @link       http://nettephp.com
 * @category   Nette
 * @package    Nette
 * @version    $Id$
 */

/*namespace Nette;*/



require_once dirname(__FILE__) . '/Object.php';



/**
 * Nette::Environment helper.
 *
 * @author     David Grudl
 * @copyright  Copyright (c) 2004, 2008 David Grudl
 * @package    Nette
 */
class Configurator extends Object
{
	/** @var bool */
	public $useCache;

	/** @var string */
	public $defaultConfigFile = '%appDir%/config.ini';

	/** @var array */
	public $defaultServices = array(
		'Nette::Application::Application' => 'Nette::Application::Application',
		'Nette::Web::IHttpRequest' => 'Nette::Web::HttpRequest',
		'Nette::Web::IHttpResponse' => 'Nette::Web::HttpResponse',
		'Nette::Web::IUser' => 'Nette::Web::User',
		'Nette::Caching::ICacheStorage' => array(__CLASS__, 'createCacheStorage'),
		'Nette::Web::Session' => 'Nette::Web::Session',
	);



	/**
	 * Detect environment mode.
	 * @param  string mode name
	 * @return bool
	 */
	public function detect($name)
	{
		switch ($name) {
		case 'environment':
			// environment name autodetection
			if (defined('ENVIRONMENT')) {
				return ENVIRONMENT;

			} elseif ($this->detect('console')) {
				return Environment::CONSOLE;

			} else {
				return Environment::getMode('live') ? Environment::PRODUCTION : Environment::DEVELOPMENT;
			}

		case 'live':
			// detects production mode by server IP address
			if (PHP_SAPI === 'cli') {
				return FALSE;

			} elseif (isset($_SERVER['SERVER_ADDR'])) {
				$oct = explode('.', $_SERVER['SERVER_ADDR']);
				return (count($oct) !== 4) || ($oct[0] !== '10' && $oct[0] !== '127' && ($oct[0] !== '171' || $oct[1] < 16 || $oct[1] > 31)
					&& ($oct[0] !== '169' || $oct[1] !== '254') && ($oct[0] !== '192' || $oct[1] !== '168'));

			} else {
				return TRUE;
			}

		case 'debug':
			// determines if the debugger is active
			if (defined('DEBUG_MODE')) {
				return (bool) DEBUG_MODE;

			} else {
				return !Environment::getMode('live') && isset($_REQUEST['DBGSESSID']);
				// function_exists('DebugBreak');
			}

		case 'console':
			return PHP_SAPI === 'cli';

		default:
			// unknown mode
			return NULL;
		}
	}



	/**
	 * Loads global configuration from file and process it.
	 * @param  string|Nette::Config::Config  file name or Config object
	 * @param  bool
	 * @return Nette::Config::Config
	 */
	public function loadConfig($file, $useCache)
	{
		if ($useCache === NULL) {
			if ($this->useCache === NULL) {
				$this->useCache = Environment::isLive();
			}
			$useCache = $this->useCache;
		}

		$cache = $useCache ? Environment::getCache('Nette.Environment') : NULL;

		$name = Environment::getName();
		if (isset($cache[$name])) {
			Environment::swapState($cache[$name]);
			$config = Environment::getConfig();

		} else {
			if ($file instanceof /*Nette::Config::*/Config) {
				$config = $file;
				$file = NULL;

			} else {
				if ($file === NULL) {
					$file = $this->defaultConfigFile;
				}
				$file = Environment::expand($file);
				$config = /*Nette::Config::*/Config::fromFile($file, $name, 0);
			}

			// process environment variables
			if ($config->variable instanceof /*Nette::Config::*/Config) {
				foreach ($config->variable as $key => $value) {
					Environment::setVariable($key, $value);
				}
			}

			if (isset($config->set->include_path)) {
				$config->set->include_path = strtr($config->set->include_path, ';', PATH_SEPARATOR);
			}

			$config->expand();
			$config->setReadOnly();

			// process services
			$locator = Environment::getServiceLocator();
			if ($config->service instanceof /*Nette::Config::*/Config) {
				foreach ($config->service as $key => $value) {
					$locator->addService($value, str_replace('-', '::', $key));
				}
			}

			// save cache
			if ($cache) {
				$state = Environment::swapState(NULL);
				$state[0] = $config; // TODO: better!
				$cache->save($name, $state, array('files' => $file));
			}
		}


		// check temporary directory - TODO: discuss
		/*
		$dir = Environment::getVariable('tempDir');
		if ($dir && !(is_dir($dir) && is_writable($dir))) {
			trigger_error("Temporary directory '$dir' is not writable", E_USER_NOTICE);
		}
		*/

		// process ini settings
		if ($config->set instanceof /*Nette::Config::*/Config) {
			if (!function_exists('ini_set')) {
				throw new /*::*/NotSupportedException('Function ini_set() is not enabled.');
			}

			foreach ($config->set as $key => $value) {
				ini_set(strtr($key, '-', '.'), $value);
			}
		}

		// define constants
		if ($config->const instanceof /*Nette::Config::*/Config) {
			foreach ($config->const as $key => $value) {
				define($key, $value);
			}
		}

		// set modes
		if (isset($config->mode)) {
			foreach($config->mode as $mode => $state) {
				Environment::setMode($mode, $state);
			}
		}

		return $config;
	}



	/********************* service factories ****************d*g**/



	/**
	 * Get initial instance of service locator.
	 * @return IServiceLocator
	 */
	public function createServiceLocator()
	{
		$locator = new ServiceLocator;
		foreach ($this->defaultServices as $name => $service) {
			$locator->addService($service, $name);
		}
		return $locator;
	}



	/**
	 * @return Nette::Caching::ICacheStorage
	 */
	public static function createCacheStorage()
	{
		return new /*Nette::Caching::*/FileStorage(Environment::getVariable('cacheBase'));
	}

}