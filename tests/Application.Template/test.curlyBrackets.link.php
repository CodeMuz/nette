<h1>Nette::Application::TemplateFilters::curlyBrackets & link test</h1>

<?php
require_once '../../Nette/loader.php';

/*use Nette::Debug;*/
/*use Nette::Environment;*/
/*use Nette::Application::Template;*/

class MockComponent
{
	function link($destination, $args = array())
	{
		array_unshift($args, $destination);
		return 'LINK(' . implode(', ', $args) . ')';
	}

	function ajaxLink($destination = NULL, $args = array())
	{
		array_unshift($args, $destination);
		return 'AJAXLINK(' . implode(', ', $args) . ')';
	}
}

$tmpDir = dirname(__FILE__) . '/tmp';
foreach (glob("$tmpDir/*") as $file) unlink($file); // delete all files

Environment::setVariable('tempDir', $tmpDir);

$template = new Template;
$template->setFile(dirname(__FILE__) . '/templates/curly-brackets-link.phtml');
$template->registerFilter(/*Nette::Application::*/'TemplateFilters::curlyBrackets');
$template->component = new MockComponent;
$template->view = 'login';
$template->render();
