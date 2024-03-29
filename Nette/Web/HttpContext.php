<?php

/**
 * Nette Framework
 *
 * @copyright  Copyright (c) 2004, 2010 David Grudl
 * @license    http://nettephp.com/license  Nette license
 * @link       http://nettephp.com
 * @category   Nette
 * @package    Nette\Web
 */

/*namespace Nette\Web;*/



/**
 * HTTP-specific tasks.
 *
 * @copyright  Copyright (c) 2004, 2010 David Grudl
 * @package    Nette\Web
 */
class HttpContext extends /*Nette\*/Object
{


	/**
	 * Attempts to cache the sent entity by its last modification date
	 * @param  string|int|DateTime  last modified time
	 * @param  string  strong entity tag validator
	 * @return bool
	 */
	public function isModified($lastModified = NULL, $etag = NULL)
	{
		$response = $this->getResponse();
		$request = $this->getRequest();

		if ($lastModified) {
			$response->setHeader('Last-Modified', $response->date($lastModified));
		}
		if ($etag) {
			$response->setHeader('ETag', '"' . addslashes($etag) . '"');
		}

		$ifNoneMatch = $request->getHeader('If-None-Match');
		if ($ifNoneMatch === '*') {
			$match = TRUE; // match, check if-modified-since

		} elseif ($ifNoneMatch !== NULL) {
			$etag = $response->getHeader('ETag');

			if ($etag == NULL || strpos(' ' . strtr($ifNoneMatch, ",\t", '  '), ' ' . $etag) === FALSE) {
				return TRUE;

			} else {
				$match = TRUE; // match, check if-modified-since
			}
		}

		$ifModifiedSince = $request->getHeader('If-Modified-Since');
		if ($ifModifiedSince !== NULL) {
			$lastModified = $response->getHeader('Last-Modified');
			if ($lastModified != NULL && strtotime($lastModified) <= strtotime($ifModifiedSince)) {
				$match = TRUE;

			} else {
				return TRUE;
			}
		}

		if (empty($match)) {
			return TRUE;
		}

		$response->setCode(IHttpResponse::S304_NOT_MODIFIED);
		return FALSE;
	}



	/********************* backend ****************d*g**/



	/**
	 * @return Nette\Web\IHttpRequest
	 */
	public function getRequest()
	{
		return /*Nette\*/Environment::getHttpRequest();
	}



	/**
	 * @return Nette\Web\IHttpResponse
	 */
	public function getResponse()
	{
		return /*Nette\*/Environment::getHttpResponse();
	}

}
