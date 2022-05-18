<?php

namespace BuzzingPixel\Treasury\Model;

use BuzzingPixel\Treasury\Service\Data\Base;

class ValidationResult extends Base
{
	// Model properties
	protected $hasErrors = 'bool';
	protected $errors = 'array';

	/**
	 * hasErrors on set - prevent this property from being set directly
	 */
	protected function hasErrors__onSet($val)
	{
		return $this->hasErrors;
	}

	/**
	 * hasErrors on set - prevent this property from being set directly
	 */
	protected function errors__onSet($val)
	{
		return $this->errors;
	}

	/**
	 * Add error
	 *
	 * @param string $error
	 */
	public function addError($error)
	{
		$this->hasErrors = true;
		$this->errors[] = $error;
	}

	/**
	 * Add errors
	 *
	 * @param array $errors
	 */
	public function addErrors($errors)
	{
		foreach ($errors as $error) {
			$this->addError($error);
		}
	}
}
