<?php

/**
 * Test: Nette\Object extension method 5.3
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 * @phpversion 5.3
 */

/*use Nette\Object;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';

require dirname(__FILE__) . '/Object.inc';



TestClass::extensionMethod('join',
	function (TestClass $that, $separator) {
		return $that->foo . $separator . $that->bar;
	}
);

$obj = new TestClass('Hello', 'World');
dump( $obj->join('*') );



__halt_compiler();

------EXPECT------
string(11) "Hello*World"
