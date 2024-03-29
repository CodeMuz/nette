<?php

/**
 * Test: Nette\ComponentContainer iterator.
 *
 * @author     David Grudl
 * @category   Nette
 * @package    Nette
 * @subpackage UnitTests
 */

/*use Nette\Component;*/
/*use Nette\ComponentContainer;*/
/*use Nette\Forms\Button;*/



require dirname(__FILE__) . '/../NetteTest/initialize.php';



class ComponentX extends Component
{
}

$c = new ComponentContainer(NULL, 'top');

$c->addComponent(new ComponentContainer, 'one');
$c->addComponent(new ComponentX, 'two');
$c->addComponent(new Button('label'), 'button1');

$c->getComponent('one')->addComponent(new ComponentX, 'inner');
$c->getComponent('one')->addComponent(new ComponentContainer, 'inner2');
$c->getComponent('one')->getComponent('inner2')->addComponent(new Button('label'), 'button2');



output("==> Normal:");
foreach ($c->getComponents() as $name => $component) {
	output("$name [{$component->reflection->name}]");
}





output("==> Filter:");
foreach ($c->getComponents(FALSE, 'Nette\Forms\Button') as $name => $component) {
	output("$name [{$component->reflection->name}]");
}





output("==> RecursiveIteratorIterator:");
foreach (new RecursiveIteratorIterator($c->getComponents(), 1) as $name => $component) {
	output("$name [{$component->reflection->name}]");
}





output("==> Recursive:");
foreach ($c->getComponents(TRUE) as $name => $component) {
	output("$name [{$component->reflection->name}]");
}




output("==> Recursive CHILD_FIRST:");
foreach ($c->getComponents(-1) as $name => $component) {
	output("$name [{$component->reflection->name}]");
}




output("==> Recursive & filter I:");
foreach ($c->getComponents(TRUE, 'Nette\Forms\Button') as $name => $component) {
	output("$name [{$component->reflection->name}]");
}




output("==> Recursive & filter II:");
foreach ($c->getComponents(TRUE, 'Nette\ComponentContainer') as $name => $component) {
	output("$name [{$component->reflection->name}]");
}



__halt_compiler();

------EXPECT------
==> Normal:

one [%ns%ComponentContainer]

two [ComponentX]

button1 [%ns%Button]

==> Filter:

button1 [%ns%Button]

==> RecursiveIteratorIterator:

one [%ns%ComponentContainer]

inner [ComponentX]

inner2 [%ns%ComponentContainer]

button2 [%ns%Button]

two [ComponentX]

button1 [%ns%Button]

==> Recursive:

one [%ns%ComponentContainer]

inner [ComponentX]

inner2 [%ns%ComponentContainer]

button2 [%ns%Button]

two [ComponentX]

button1 [%ns%Button]

==> Recursive CHILD_FIRST:

inner [ComponentX]

button2 [%ns%Button]

inner2 [%ns%ComponentContainer]

one [%ns%ComponentContainer]

two [ComponentX]

button1 [%ns%Button]

==> Recursive & filter I:

button2 [%ns%Button]

button1 [%ns%Button]

==> Recursive & filter II:

one [%ns%ComponentContainer]

inner2 [%ns%ComponentContainer]
