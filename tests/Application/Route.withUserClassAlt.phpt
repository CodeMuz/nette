<?php

/**
 * Test: Nette\Application\Route with WithUserClassAlt
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette\Application
 * @subpackage UnitTests
 */

/*use Nette\Application\Route;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';

require dirname(__FILE__) . '/Route.inc';



$route = new Route('<presenter>/<id>', array(
	'id' => array(
		Route::PATTERN => '\d{1,3}',
	),
), Route::FULL_META);

testRoute($route, '/presenter/12/');

testRoute($route, '/presenter/1234');

testRoute($route, '/presenter/');



__halt_compiler();

------EXPECT------
==> /presenter/12/

string(9) "Presenter"

array(2) {
	"id" => string(2) "12"
	"test" => string(9) "testvalue"
}

string(28) "/presenter/12?test=testvalue"

==> /presenter/1234

not matched

==> /presenter/

not matched
