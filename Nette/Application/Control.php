<?php

/**
 * Nette Framework
 *
 * Copyright (c) 2004, 2008 David Grudl (http://davidgrudl.com)
 *
 * This source file is subject to the "Nette license" that is bundled
 * with this package in the file license.txt.
 *
 * For more information please see http://nettephp.com
 *
 * @copyright  Copyright (c) 2004, 2008 David Grudl
 * @license    http://nettephp.com/license  Nette license
 * @link       http://nettephp.com
 * @category   Nette
 * @package    Nette\Application
 * @version    $Id$
 */

/*namespace Nette\Application;*/



require_once dirname(__FILE__) . '/../Application/PresenterComponent.php';

require_once dirname(__FILE__) . '/../Application/IRenderable.php';



/**
 * Control is renderable component.
 *
 * @author     David Grudl
 * @copyright  Copyright (c) 2004, 2008 David Grudl
 * @package    Nette\Application
 */
abstract class Control extends PresenterComponent implements IPartiallyRenderable
{
	/** @var Nette\Templates\ITemplate */
	private $template;

	/** @var array */
	private $invalidSnippets = array();



	/********************* template factory ****************d*g**/



	/**
	 * @return Nette\Templates\ITemplate
	 */
	final public function getTemplate()
	{
		if ($this->template === NULL) {
			$value = $this->createTemplate();
			if (!($value instanceof /*Nette\Templates\*/ITemplate || $value === NULL)) {
				$class = get_class($value);
				throw new /*\*/UnexpectedValueException("The Nette\Templates\ITemplate object was expected, '$class' was given.");
			}
			$this->template = $value;
		}
		return $this->template;
	}



	/**
	 * @return Nette\Templates\ITemplate
	 */
	protected function createTemplate()
	{
		$template = new /*Nette\Templates\*/Template;
		$presenter = $this->getPresenter(FALSE);

		// default parameters
		$template->component = $this; // DEPRECATED!
		$template->control = $this;
		$template->presenter = $presenter;
		$template->baseUri = /*Nette\*/Environment::getVariable('baseUri');

		// flash message
		if ($presenter !== NULL && $presenter->hasFlashSession()) {
			$id = $this->getParamId('flash');
			$template->flashes = $presenter->getFlashSession()->$id;
		}
		if (!isset($template->flashes) || !is_array($template->flashes)) {
			$template->flashes = array();
		}

		// default helpers
		$template->registerHelper('escape', 'Nette\Templates\TemplateHelpers::escapeHtml');
		$template->registerHelper('escapeJs', 'Nette\Templates\TemplateHelpers::escapeJs');
		$template->registerHelper('escapeCss', 'Nette\Templates\TemplateHelpers::escapeCss');
		$template->registerHelper('cache', 'Nette\Templates\CachingHelper::create');
		$template->registerHelper('snippet', 'Nette\Templates\SnippetHelper::create');
		$template->registerHelper('lower', 'Nette\String::lower');
		$template->registerHelper('upper', 'Nette\String::upper');
		$template->registerHelper('capitalize', 'Nette\String::capitalize');
		$template->registerHelper('stripTags', 'strip_tags');
		$template->registerHelper('strip', 'Nette\Templates\TemplateHelpers::strip');
		$template->registerHelper('date', 'Nette\Templates\TemplateHelpers::date');
		$template->registerHelper('nl2br', 'nl2br');
		$template->registerHelper('truncate', 'Nette\String::truncate');
		$template->registerHelper('bytes', 'Nette\TemplateHelpers::bytes');

		return $template;
	}



	/**
	 * Saves the message to template, that can be displayed after redirect.
	 * @param  string
	 * @param  string
	 * @return stdClass
	 */
	public function flashMessage($message, $type = 'info')
	{
		$id = $this->getParamId('flash');
		$messages = $this->getPresenter()->getFlashSession()->$id;
		$messages[] = $flash = (object) array(
			'message' => $message,
			'type' => $type,
		);
		$this->getTemplate()->flashes = $messages;
		$this->getPresenter()->getFlashSession()->$id = $messages;
		return $flash;
	}



	/********************* rendering ****************d*g**/



	/**
	 * Forces control or its snippet to repaint.
	 * @param  string
	 * @return void
	 */
	public function invalidateControl($snippet = NULL)
	{
		$this->invalidSnippets[$snippet] = TRUE;
	}



	/**
	 * Allows control or its snippet to not repaint.
	 * @param  string
	 * @return void
	 */
	public function validateControl($snippet = NULL)
	{
		if ($snippet === NULL) {
			$this->invalidSnippets = array();

		} else {
			unset($this->invalidSnippets[$snippet]);
		}
	}



	/**
	 * Is required to repaint the control or its snippet?
	 * @param  string  snippet name
	 * @return bool
	 */
	public function isControlInvalid($snippet = NULL)
	{
		if ($snippet === NULL) {
			if (count($this->invalidSnippets) > 0) {
				return TRUE;

			} else {
				foreach ($this->getComponents() as $component) {
					if ($component instanceof IRenderable && $component->isControlInvalid()) {
						// $this->invalidSnippets['__child'] = TRUE; // as cache
						return TRUE;
					}
				}
				return FALSE;
			}

		} else {
			return isset($this->invalidSnippets[NULL]) || isset($this->invalidSnippets[$snippet]);
		}
	}



	/**
	 * Returns snippet HTML ID.
	 * @param  string  snippet name
	 * @return string
	 */
	public function getSnippetId($name = NULL)
	{
		// HTML 4 ID & NAME: [A-Za-z][A-Za-z0-9:_.-]*
		return $this->getUniqueId() . '__' . $name;
	}

}
