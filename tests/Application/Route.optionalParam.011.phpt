<?php

/**
 * Test: Nette\Application\Route with optional sequence precedence.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette\Application
 * @subpackage UnitTests
 */

/*use Nette\Application\Route;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';

require dirname(__FILE__) . '/Route.inc';


$route = new Route('[<one>/][<two>]', array(
));

testRoute($route, '/one');


output();


$route = new Route('[<one>/]<two>', array(
	'two' => NULL,
));

testRoute($route, '/one');



__halt_compiler();

------EXPECT------
==> /one

string(14) "querypresenter"

array(3) {
	"one" => string(3) "one"
	"two" => NULL
	"test" => string(9) "testvalue"
}

string(45) "/one/?test=testvalue&presenter=querypresenter"

===

==> /one

string(14) "querypresenter"

array(3) {
	"one" => string(3) "one"
	"two" => NULL
	"test" => string(9) "testvalue"
}

string(45) "/one/?test=testvalue&presenter=querypresenter"
