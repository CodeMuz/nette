<?php

/**
 * Test: Nette\Web\UriScript modify.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette\Web
 * @subpackage UnitTests
 */

/*use Nette\Web\UriScript;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';



$uri = new UriScript('http://nettephp.com:8080/file.php?q=search');
$uri->path = '/test/';
$uri->scriptPath = '/test/index.php';

Assert::same( '/test/index.php',  $uri->scriptPath );
Assert::same( 'http://nettephp.com:8080/test/',  $uri->baseUri );
Assert::same( '/test/',  $uri->basePath );
Assert::same( '',  $uri->relativeUri );
Assert::same( '',  $uri->pathInfo );
Assert::same( 'http://nettephp.com:8080/test/?q=search',  $uri->absoluteUri );
