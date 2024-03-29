<?php

/**
 * Nette Framework
 *
 * @copyright  Copyright (c) 2004, 2010 David Grudl
 * @license    http://nettephp.com/license  Nette license
 * @link       http://nettephp.com
 * @category   Nette
 * @package    Nette\Forms
 */

/*namespace Nette\Forms;*/



/**
 * Submittable button control.
 *
 * @copyright  Copyright (c) 2004, 2010 David Grudl
 * @package    Nette\Forms
 *
 * @property   mixed $validationScope
 * @property-read bool $submittedBy
 */
class SubmitButton extends Button implements ISubmitterControl
{
	/** @var array of function(SubmitButton $sender); Occurs when the button is clicked and form is successfully validated */
	public $onClick;

	/** @var array of function(SubmitButton $sender); Occurs when the button is clicked and form is not validated */
	public $onInvalidClick;

	/** @var mixed */
	private $validationScope = TRUE;



	/**
	 * @param  string  caption
	 */
	public function __construct($caption = NULL)
	{
		parent::__construct($caption);
		$this->control->type = 'submit';
	}



	/**
	 * Sets 'pressed' indicator.
	 * @param  bool
	 * @return SubmitButton  provides a fluent interface
	 */
	public function setValue($value)
	{
		$this->value = is_scalar($value) && (bool) $value;
		$form = $this->getForm();
		if ($this->value || !is_object($form->isSubmitted())) {
			$this->value = TRUE;
			$form->setSubmittedBy($this);
		}
		return $this;
	}



	/**
	 * Tells if the form was submitted by this button.
	 * @return bool
	 */
	public function isSubmittedBy()
	{
		return $this->getForm()->isSubmitted() === $this;
	}



	/**
	 * Sets the validation scope. Clicking the button validates only the controls within the specified scope.
	 * @param  mixed
	 * @return SubmitButton  provides a fluent interface
	 */
	public function setValidationScope($scope)
	{
		// TODO: implement groups
		$this->validationScope = (bool) $scope;
		return $this;
	}



	/**
	 * Gets the validation scope.
	 * @return mixed
	 */
	final public function getValidationScope()
	{
		return $this->validationScope;
	}



	/**
	 * Fires click event.
	 * @return void
	 */
	public function click()
	{
		$this->onClick($this);
	}



	/**
	 * Submitted validator: has been button pressed?
	 * @param  ISubmitterControl
	 * @return bool
	 */
	public static function validateSubmitted(ISubmitterControl $control)
	{
		return $control->isSubmittedBy();
	}

}
