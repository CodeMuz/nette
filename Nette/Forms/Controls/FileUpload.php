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

/*use Nette\Web\HttpUploadedFile;*/



/**
 * Text box and browse button that allow users to select a file to upload to the server.
 *
 * @copyright  Copyright (c) 2004, 2010 David Grudl
 * @package    Nette\Forms
 */
class FileUpload extends FormControl
{

	/**
	 * @param  string  label
	 */
	public function __construct($label = NULL)
	{
		parent::__construct($label);
		$this->control->type = 'file';
	}



	/**
	 * This method will be called when the component (or component's parent)
	 * becomes attached to a monitored object. Do not call this method yourself.
	 * @param  IComponent
	 * @return void
	 */
	protected function attached($form)
	{
		if ($form instanceof Form) {
			if ($form->getMethod() !== Form::POST) {
				throw new /*\*/InvalidStateException('File upload requires method POST.');
			}
			$form->getElementPrototype()->enctype = 'multipart/form-data';
		}
		parent::attached($form);
	}



	/**
	 * Sets control's value.
	 * @param  array|Nette\Web\HttpUploadedFile
	 * @return FileUpload  provides a fluent interface
	 */
	public function setValue($value)
	{
		if (is_array($value)) {
			$this->value = new HttpUploadedFile($value);

		} elseif ($value instanceof HttpUploadedFile) {
			$this->value = $value;

		} else {
			$this->value = new HttpUploadedFile(NULL);
		}
		return $this;
	}



	/**
	 * Filled validator: has been any file uploaded?
	 * @param  IFormControl
	 * @return bool
	 */
	public static function validateFilled(IFormControl $control)
	{
		$file = $control->getValue();
		return $file instanceof HttpUploadedFile && $file->isOK();
	}



	/**
	 * FileSize validator: is file size in limit?
	 * @param  FileUpload
	 * @param  int  file size limit
	 * @return bool
	 */
	public static function validateFileSize(FileUpload $control, $limit)
	{
		$file = $control->getValue();
		return $file instanceof HttpUploadedFile && $file->getSize() <= $limit;
	}



	/**
	 * MimeType validator: has file specified mime type?
	 * @param  FileUpload
	 * @param  array|string  mime type
	 * @return bool
	 */
	public static function validateMimeType(FileUpload $control, $mimeType)
	{
		$file = $control->getValue();
		if ($file instanceof HttpUploadedFile) {
			$type = $file->getContentType();
			$type = strtolower(preg_replace('#\s*;.*$#', '', $type));
			if (!$type) {
				return FALSE; // cannot verify :-(
			}
			$mimeTypes = is_array($mimeType) ? $mimeType : explode(',', $mimeType);
			if (in_array($type, $mimeTypes, TRUE)) {
				return TRUE;
			}
			if (in_array(preg_replace('#/.*#', '/*', $type), $mimeTypes, TRUE)) {
				return TRUE;
			}
		}
		return FALSE;
	}

}
