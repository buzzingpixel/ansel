<?php

namespace BuzzingPixel\Treasury\API;

use BuzzingPixel\Treasury\Utility\ReflectionUtility;

abstract class BaseAPI
{
	// Property types
	private $_propertiesTypes = array();

	/**
	 * Constructor
	 */
	public function __construct()
	{
		// Get properties
		$properties = ReflectionUtility::getProtectedNonStaticProps($this);

		// Loop through the properties
		foreach ($properties as $prop) {
			// Make the property accessible to get and set value via reflection
			$prop->setAccessible(true);

			// Get the property value
			$value = $prop->getValue($this);

			$this->_propertiesTypes[$prop->name] = $value;

			// Set initial value
			if ($value === 'int') {
				$prop->setValue($this, 0);
			} elseif ($value === 'float') {
				$prop->setValue($this, 0);
			} elseif ($value === 'array') {
				$prop->setValue($this, array());
			} elseif ($value === 'bool') {
				$prop->setValue($this, null);
			} elseif ($value === 'string') {
				$prop->setValue($this, '');
			} else {
				$prop->setValue($this, null);
			}
		}
	}



	/**
	 * Get magic method
	 *
	 * @param string $name
	 * @return mixed
	 */
	public function __get($name)
	{
		// Make sure property exists
		if (property_exists($this, $name)) {
			return $this->{$name};
		}

		return null;
	}

	/**
	 * Set magic method
	 *
	 * @param string $name
	 * @param mixed $value
	 */
	public function __set($name, $value)
	{
		// Make sure property is settable
		if (isset($this->_propertiesTypes[$name])) {
			$type = $this->_propertiesTypes[$name];

			// Cast value properly
			if ($type === 'int') {
				$value = (int) $value;
			} elseif ($type === 'float') {
				$value = (float) $value;
			} elseif ($type === 'array') {
				if (gettype($value) !== 'array') {
					$value = explode('|', $value);
				}
			} elseif ($type === 'bool') {
				if (gettype($value) !== 'boolean') {
					$value = $value === 'y' || $value === '1' || $value === 'yes' || $value === 1;
				}
			} elseif ($type === 'string') {
				$value = (string) $value;
			}

			// Set the value
			$this->{$name} = $value;
		}

		// Do not allow overloading
		return null;
	}

	/**
	 * __call Magic method for setting properies by method
	 * @param string $name
	 * @param array $args
	 * @return self
	 */
	public function __call($name, $args)
	{
		if (count($args) === 1 && isset($args[0])) {
			$this->__set($name, $args[0]);
		}

		return $this;
	}
}
