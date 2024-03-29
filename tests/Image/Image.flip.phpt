<?php

/**
 * Test: Nette\Image flip.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

/*use Nette\Image;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';



$image = Image::fromFile('images/logo.gif');
$flipped = $image->resize(-100, -100);
$flipped->send(Image::GIF);
