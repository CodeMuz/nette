<?php

/**
 * Test: Nette\Web\UriScript parse.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette\Web
 * @subpackage UnitTests
 */

/*use Nette\Web\UriScript;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';



$uri = new UriScript('http://nettephp.com:8080/file.php?q=search');
Assert::same( '', $uri->scriptPath );
Assert::same( 'http://nettephp.com:8080',  $uri->baseUri );
Assert::same( '', $uri->basePath );
Assert::same( 'file.php',  $uri->relativeUri );
Assert::same( '/file.php',  $uri->pathInfo );
