<h1>Nette\Templates\CurlyBracketsFilter test</h1>

<?php
require_once '../../Nette/loader.php';

/*use Nette\Debug;*/
/*use Nette\Environment;*/
/*use Nette\Templates\Template;*/

$tmpDir = dirname(__FILE__) . '/tmp';
foreach (glob("$tmpDir/*") as $file) unlink($file); // delete all files

Environment::setVariable('tempDir', $tmpDir);

$template = new Template;
$template->setFile(dirname(__FILE__) . '/templates/curly-brackets.phtml');
$template->registerFilter(/*Nette\Templates\*/'CurlyBracketsFilter::invoke');
$template->hello = '<i>Hello</i>';
$template->people = array('John', 'Mary', 'Paul');
$template->render();
