<?php

/**
 * Test: Nette\Application\Route with ExtraDefaultParam
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette\Application
 * @subpackage UnitTests
 */

/*use Nette\Application\Route;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';

require dirname(__FILE__) . '/Route.inc';



$route = new Route('<presenter>/<action>/<id \d{1,3}>/', array(
	'extra' => NULL,
));


testRoute($route, '/presenter/action/12/any');

testRoute($route, '/presenter/action/12');

testRoute($route, '/presenter/action/1234');

testRoute($route, '/presenter/action/');

testRoute($route, '/presenter');

testRoute($route, '/');



__halt_compiler();

------EXPECT------
==> /presenter/action/12/any

not matched

==> /presenter/action/12

string(9) "Presenter"

array(4) {
	"action" => string(6) "action"
	"id" => string(2) "12"
	"extra" => NULL
	"test" => string(9) "testvalue"
}

string(36) "/presenter/action/12/?test=testvalue"

==> /presenter/action/1234

not matched

==> /presenter/action/

not matched

==> /presenter

not matched

==> /

not matched
