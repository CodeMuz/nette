<?php

/**
 * Test: Nette\Image rotating.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

/*use Nette\Image;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';



if (GD_BUNDLED === 0) {
	NetteTestHelpers::skip('Requires PHP extension GD in bundled version.');
}



$image = Image::fromFile('images/logo.gif');
$rotated = $image->rotate(30, Image::rgb(0, 0, 0));
$rotated->send(Image::GIF);



__halt_compiler();
