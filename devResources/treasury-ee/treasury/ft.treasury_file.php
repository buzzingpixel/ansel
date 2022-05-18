<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

use BuzzingPixel\Treasury\Service\BaseFieldType;
use BuzzingPixel\Treasury\Controller\FileField\Settings;
use BuzzingPixel\Treasury\Controller\FileField\Field;
use BuzzingPixel\Treasury\Controller\FilesTag;

class Treasury_file_ft extends BaseFieldType
{
	/**
	 * Info attribute requiered for EE
	 *
	 * @var array
	 */
	public $info = array(
		'name' => TREASURY_FILE_NAME,
		'version' => TREASURY_VER
	);

	/**
	 * Compatibility
	 * @param string $name
	 * @return bool
	 */
	// @codingStandardsIgnoreStart
	public function accepts_content_type($name) // @codingStandardsIgnoreEnd
	{
		$compatibility = array(
			'blocks/1',
			'channel',
			'grid',
			'low_variables',
			'fluid_field',
		);

		return in_array($name, $compatibility, false);
	}

	/**
	 * Field settings
	 *
	 * @param array $data Existing field setting data
	 * @return array
	 */
	public function display_settings($data)
	{
		// Load the controller and render
		$controller = new Settings($data);
		return array('field_options_treasury_file' => array(
			'label' => 'field_options',
			'group' => 'treasury_file',
			'settings' => $controller->render()
		));
	}

	/**
	 * Grid field settings
	 *
	 * @param array $data Existing field setting data
	 * @return array
	 */
	public function grid_display_settings($data)
	{
		// Load the controller and render
		$controller = new Settings($data);
		return array(
			'field_options' => $controller->render()
		);
	}

	/**
	 * Display Low Variables field settings
	 *
	 * @param array $data Existing field setting data
	 * @return array
	 */
	public function var_display_settings($data)
	{
		// Load the controller and render
		$controller = new Settings($data);
		return $controller->renderLowVars();
	}

	/**
	 * Validate field settings
	 *
	 * @param array $data Field setting data
	 */
	public function validate_settings($data)
	{
		// Get the controller
		$controller = new Settings($_POST);

		// Return validation result
		return $controller->validate();
	}

	/**
	 * Validate grid field settings
	 *
	 * @param array $data Field setting data
	 */
	public function grid_validate_settings($data)
	{
		// Get the controller
		$controller = new Settings($data);

		// Return validation result
		return $controller->validate();
	}

	/**
	 * Save settings
	 *
	 * @param array $data Field setting data
	 * @return array
	 */
	public function save_settings($data)
	{
		// Get the controller
		$controller = new Settings($data);

		// Return validation result
		return $controller->save();
	}

	/**
	 * Save Low Variables settings
	 *
	 * @param array $data Field settings data
	 * @return array
	 */
	public function var_save_settings($data)
	{
		// Get the controller
		$controller = new Settings($data);

		// Return validation result
		return $controller->save();
	}

	/**
	 * Display field
	 *
	 * @param mixed $data Existing field data
	 * @return string
	 */
	public function display_field($data)
	{
		// Get the controller
		$controller = new Field($this->getFieldSettings(), $data);

		// Return validation result
		return $controller->render();
	}

	/**
	 * Validate field data
	 *
	 * @param string $data
	 * @return boolean
	 */
	public function validate($data)
	{
		// Get the controller
		$controller = new Field($this->getFieldSettings(), $data);

		// Return validation result
		return $controller->validate();
	}

	/**
	 * Display Low Variables field
	 *
	 * @param mixed $data Existing field data
	 * @return string
	 */
	public function var_display_field($data)
	{
		return $this->display_field($data);
	}

	// Set field type as tag pair
	public $has_array_data = true;

	/**
	 * Replace tag
	 *
	 * @param bool|string $fieldData
	 * @param array $tagParams
	 * @param $tagData bool|string
	 * @return string
	 */
	public function replace_tag(
		$fieldData = false,
		$tagParams = array(),
		$tagData = false
	) {
		// Set the site ID
		$tagParams['site_id'] = ee()->config->item('site_id');

		// Set the file id
		$tagParams['id'] = $fieldData ?: null;

		// Go ahead and return empty string now if there is no ID
		if (! $tagParams['id']) {
			return '';
		}

		// Get the FilesTag controller and render
		$controller = new FilesTag($tagParams, $tagData);
		return $controller->render();
	}

	/**
	 * Low Variables Replace tag
	 *
	 * @param bool|string $fieldData
	 * @param array $tagParams
	 * @param bool|string $tagData
	 * @return string
	 */
	public function var_replace_tag(
		$fieldData = false,
		$tagParams = array(),
		$tagData = false
	) {
		return $this->replace_tag($fieldData, $tagParams, $tagData ?: false);
	}
}
