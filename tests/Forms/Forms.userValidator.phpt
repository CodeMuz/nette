<?php

/**
 * Test: Nette\Forms user validator.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette\Forms
 * @subpackage UnitTests
 */

/*use Nette\Forms\Form;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';



function myValidator1($item, $arg)
{
	return $item->getValue() != $arg;
}


$form = new Form();
$form->addText('name', 'Text:', 10)
	->addRule('myValidator1', 'Value %d is not allowed!', 11)
	->addRule(~'myValidator1', 'Value %d is required!', 22);



__halt_compiler();

------EXPECT------
