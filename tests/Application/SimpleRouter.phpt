<?php

/**
 * Test: Nette\Application\SimpleRouter basic functions.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette\Application
 * @subpackage UnitTests
 */

/*use Nette\Application\SimpleRouter;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';

require dirname(__FILE__) . '/SimpleRouter.inc';



$router = new SimpleRouter(array(
	'id' => 12,
	'any' => 'anyvalue',
));

$httpRequest = new MockHttpRequest;
$httpRequest->setQuery(array(
	'presenter' => 'myPresenter',
	'action' => 'action',
	'id' => '12',
	'test' => 'testvalue',
));

$req = $router->match($httpRequest);
Assert::same( "myPresenter",  $req->getPresenterName() );
Assert::same( "action",  $req->params["action"] );
Assert::same( "12",  $req->params["id"] );
Assert::same( "testvalue",  $req->params["test"] );
Assert::same( "anyvalue",  $req->params["any"] );

$url = $router->constructUrl($req, $httpRequest);
Assert::same( "http://nettephp.com/file.php?action=action&test=testvalue&presenter=myPresenter",  $url );
