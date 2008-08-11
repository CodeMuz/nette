<?php

/**
 * Nette Framework
 *
 * Copyright (c) 2004, 2008 David Grudl (http://davidgrudl.com)
 *
 * This source file is subject to the "Nette license" that is bundled
 * with this package in the file license.txt.
 *
 * For more information please see http://nettephp.com/
 *
 * @copyright  Copyright (c) 2004, 2008 David Grudl
 * @license    http://nettephp.com/license  Nette license
 * @link       http://nettephp.com/
 * @category   Nette
 * @package    Nette::Forms
 */

/*namespace Nette::Forms;*/



require_once dirname(__FILE__) . '/../../Forms/Controls/Button.php';

require_once dirname(__FILE__) . '/../../Forms/ISubmitterControl.php';



/**
 * Submittable button control.
 *
 * @author     David Grudl
 * @copyright  Copyright (c) 2004, 2008 David Grudl
 * @package    Nette::Forms
 * @version    $Revision$ $Date$
 */
class SubmitButton extends Button implements ISubmitterControl
{
	/** @var array  click event handlers: function($sender) */
	public $onClick;



	/**
	 * @param  string  label
	 */
	public function __construct($label)
	{
		parent::__construct($label);
		$this->control->type = 'submit';
	}



	/**
	 * Tells if the form was submitted by this button.
	 * @return bool
	 */
	public function isSubmittedBy()
	{
		return (bool) $this->value;
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
	 * @param  IFormControl
	 * @return bool
	 */
	public static function validateSubmitted(IFormControl $control)
	{
		return $control->isSubmittedBy();
	}

}