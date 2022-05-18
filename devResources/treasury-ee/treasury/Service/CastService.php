<?php

namespace BuzzingPixel\Treasury\Service;

class CastService
{
	/**
	 * Cast model values
	 *
	 * @param array $config Model config
	 * @param mixed $val
	 * @param boolean $castBools
	 * @return mixed
	 */
	public static function cast($config, $val, $castBools = false)
	{
		if (gettype($config) === 'string') {
			$config = array(
				'type' => $config,
				'decimals' => 0
			);
		}

		// Check the type and cast appropriately
		if ($config['type'] === 'string') {
			$val = (string) $val;
		} elseif ($config['type'] === 'number') {
			if ($config['decimals'] < 1) {
				$val = (int) $val;
			} else {
				$val = (float) $val;
			}
		} elseif ($config['type'] === 'int') {
			$val = (int) $val;
		} elseif ($config['type'] === 'float') {
			$val = (float) $val;
		} elseif ($config['type'] === 'bool') {
			if ($castBools) {
				$val = $val === 1 || $val === '1' || $val === 'true' || $val === true;
			} else {
				$val = $val === 1 || $val === '1' || $val === 'true' || $val === true ? 1 : 0;
			}
		} elseif ($config['type'] === 'array' || $config['type'] === 'intArray') {
			if (gettype($val) !== 'array') {
				$val = explode(',', $val);
			}

			if ($config['type'] === 'intArray') {
				foreach ($val as $valKey => $valVal) {
					$val[$valKey] = (int) $valVal;
				}
			}
		}

		return $val;
	}
}
