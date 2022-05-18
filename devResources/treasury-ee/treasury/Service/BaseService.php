<?php

namespace BuzzingPixel\Treasury\Service;

use BuzzingPixel\Treasury\Service\CastService;

class BaseService
{
	private $propertiesMap = array();

	/**
	 * Constructor
	 */
	public function __construct()
	{
		/**
		 * Set up properties map
		 */

		// Get reflection class
		$class = new \ReflectionClass($this);

		// Get protected properties
		$properties = $class->getProperties(\ReflectionProperty::IS_PROTECTED);

		// Loop through the protected properties
		foreach ($properties as $property) {
			// Make the property accessible to us
			$property->setAccessible(true);

			// Set the property map
			$this->propertiesMap[$property->getName()] = $property->getValue(
				$this
			);

			// Set the value of the protected proeprty to null
			$property->setValue($this, null);
		}
	}

	/**
	 * Get magic method
	 *
	 * @param string $name
	 * @return array
	 */
	public function __get($name)
	{
		if (isset($this->propertiesMap[$name])) {
			return CastService::cast(
				$this->propertiesMap[$name],
				$this->{$name}
			);
		}

		return null;
	}

	/**
	 * Set magic method
	 *
	 * @param string $name
	 * @param mixed $val
	 */
	public function __set($name, $val)
	{
		if (isset($this->propertiesMap[$name])) {
			$this->{$name} = CastService::cast(
				$this->propertiesMap[$name],
				$val
			);
		}

		// Don’t allow class overloading
		return null;
	}
}
