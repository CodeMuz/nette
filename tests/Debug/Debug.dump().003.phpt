<?php

/**
 * Test: Nette\Debug::dump() in production mode.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

/*use Nette\Debug;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';



Debug::$consoleMode = FALSE;
Debug::$productionMode = TRUE;



Debug::dump('sensitive data');

echo Debug::dump('forced', TRUE);



__halt_compiler();

------EXPECT------
<pre class="dump"><span>string</span>(6) "forced"
</pre>
