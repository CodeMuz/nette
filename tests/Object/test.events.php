<h1>Nette::Object events test</h1>

<pre>
<?php

require_once '../../Nette/loader.php';



function handler($val)
{
	echo __METHOD__ . " says $val\n";
}


class Handler
{

	function __invoke($val)
	{
		echo __METHOD__ . " says $val\n";
	}
}



class Test extends /*Nette::*/Object
{
	private $onClick1;

	protected $onClick2;

	public $onClick3;

	public $onClick4 = 'nonevent';

	static public $onClick5;
}



$obj = new Test;
echo "\n\n<h2>Invoking events</h2>\n";

try {
	echo 'private ';

	$obj->onClick1(123);
	echo "SUCCESS\n";

} catch (Exception $e) {
	echo get_class($e), ': ', $e->getMessage(), "\n\n";
}


try {
	echo 'protected ';

	$obj->onClick2(123);
	echo "SUCCESS\n";

} catch (Exception $e) {
	echo get_class($e), ': ', $e->getMessage(), "\n\n";
}


try {
	echo 'public ';

	$obj->onClick3(123);
	echo "SUCCESS\n";

} catch (Exception $e) {
	echo get_class($e), ': ', $e->getMessage(), "\n\n";
}


try {
	echo 'nonevent ';

	$obj->onClick4(123);
	echo "SUCCESS\n";

} catch (Exception $e) {
	echo get_class($e), ': ', $e->getMessage(), "\n\n";
}


try {
	echo 'static public ';

	$obj->onClick5(123);
	echo "SUCCESS\n";

} catch (Exception $e) {
	echo get_class($e), ': ', $e->getMessage(), "\n\n";
}



echo "\n\n<h2>Attaching handler</h2>\n";

$obj->onClick3[] = 'handler';

echo "Invoking: ", $obj->onClick3('hello'), "\n";


echo "\n\n<h2>Attaching object handler</h2>\n";

$obj->onClick3[] = new Handler;

echo "Invoking: ", $obj->onClick3('hello'), "\n";