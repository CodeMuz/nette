<?php

/**
 * Test: Nette\Application\Route with WithUserClassAndUserPattern
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette\Application
 * @subpackage UnitTests
 */

/*use Nette\Application\Route;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';

require dirname(__FILE__) . '/Route.inc';



Route::addStyle('#numeric');
Route::setStyleProperty('#numeric', Route::PATTERN, '\d{1,3}');

$route = new Route('<presenter>/<id [\d.]+#numeric>', array());

testRoute($route, '/presenter/12.34/');

testRoute($route, '/presenter/123x');

testRoute($route, '/presenter/');



__halt_compiler();

------EXPECT------
==> /presenter/12.34/

string(9) "Presenter"

array(2) {
	"id" => string(5) "12.34"
	"test" => string(9) "testvalue"
}

string(31) "/presenter/12.34?test=testvalue"

==> /presenter/123x

not matched

==> /presenter/

not matched
