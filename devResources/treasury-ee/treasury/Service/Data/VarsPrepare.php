<?php

namespace BuzzingPixel\Treasury\Service\Data;

class VarsPrepare
{
	/**
	 * Process
	 *
	 * @param object $collection
	 * @param string namespace
	 * @return mixed
	 */
	public static function process(
		\BuzzingPixel\Treasury\Service\Data\Collection $collection,
		$namespace = ''
	)
	{
		// Start vars array
		$vars = array();

		// Get the total
		$total = count($collection);

		// Start a counter
		$counter = 0;

		// Set the namespace
		$namespace = $namespace ? "{$namespace}:" : '';

		// Loop through the collection
		foreach ($collection as $model) {
			// Loop through model properties
			foreach ($model as $key => $val) {
				// As long as the type is not an object
				if ($model->getType($key) !== 'object') {
					// Set value with namespace
					$vars[$counter]["{$namespace}{$key}"] = $val;
				}
			}

			// Set count variables
			$vars[$counter]["{$namespace}index"] = $counter;
			$vars[$counter]["{$namespace}count"] = $counter + 1;
			$vars[$counter]["{$namespace}total"] = $total;
			$vars[$counter]["{$namespace}total_results"] = $total;

			// Increment counter
			$counter++;
		}

		return $vars;
	}
}
