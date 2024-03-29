<?php

/**
 * Test: Nette\Caching\FileStorage items dependency test.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette\Caching
 * @subpackage UnitTests
 */

/*use Nette\Caching\Cache;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';



$key = 'nette';
$value = 'rulez';

// temporary directory
define('TEMP_DIR', dirname(__FILE__) . '/tmp');
NetteTestHelpers::purge(TEMP_DIR);


$cache = new Cache(new /*Nette\Caching\*/FileStorage(TEMP_DIR));


output('Writing cache...');
$cache->save($key, $value, array(
	Cache::ITEMS => array('dependent'),
));

dump( isset($cache[$key]), 'Is cached?' );

output('Modifing dependent cached item');
$cache['dependent'] = 'hello world';

dump( isset($cache[$key]), 'Is cached?' );

output('Writing cache...');
$cache->save($key, $value, array(
	Cache::ITEMS => 'dependent',
));

dump( isset($cache[$key]), 'Is cached?' );

output('Modifing dependent cached item');
sleep(2);
$cache['dependent'] = 'hello europe';

dump( isset($cache[$key]), 'Is cached?' );

output('Writing cache...');
$cache->save($key, $value, array(
	Cache::ITEMS => 'dependent',
));

dump( isset($cache[$key]), 'Is cached?' );

output('Deleting dependent cached item');
$cache['dependent'] = NULL;

dump( isset($cache[$key]), 'Is cached?' );



__halt_compiler();

------EXPECT------
Writing cache...

Is cached? bool(TRUE)

Modifing dependent cached item

Is cached? bool(FALSE)

Writing cache...

Is cached? bool(TRUE)

Modifing dependent cached item

Is cached? bool(FALSE)

Writing cache...

Is cached? bool(TRUE)

Deleting dependent cached item

Is cached? bool(FALSE)
