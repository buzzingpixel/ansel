<?php

namespace BuzzingPixel\Treasury\Service;

use BuzzingPixel\Treasury\Service\CP\Assets;

abstract class BaseFieldType extends \EE_Fieldtype
{
	/**
	 * EE_Fieldtype has one abstract class with we must also declare
	 */
	public function display_field($data) {
		return $data;
	}

	/**
	 * FieldType constructor
	 */
	public function __construct()
	{
		$configPaths = ee()->config->_config_paths;

		// Make sure Treasury is really being requested and we're in the CP
		if (
			REQ === 'CP' &&
			ee()->uri->segment(3) !== 'package_settings' &&
			(
				in_array(PATH_THIRD . 'treasury/', $configPaths) ||
				in_array(PATH_THIRD . 'low_variables/', $configPaths)
			)
		) {
			// Make sure the package path is available
			if (! in_array(PATH_THIRD . 'treasury/', ee()->load->get_package_paths())) {
				ee()->load->add_package_path(PATH_THIRD . 'treasury/');
			}

			// Make sure the lang file is available
			ee()->lang->loadfile('treasury');

			// Set assets
			Assets::set();
		}

		// Make sure the parent constructor runs
		parent::__construct();
	}

	/**
	 * Set field compatibility
	 *
	 * @param string $name
	 * @return bool
	 */
	public function accepts_content_type($name)
	{
		$compatibility = array(
			'blocks/1',
			'channel',
			'grid',
			'low_variables'
		);

		return in_array($name, $compatibility);
	}

	/**
	 * Format field settings
	 */
	protected function getFieldSettings()
	{
		$fieldSettings = $this->settings;

		if (isset($fieldSettings['grid_field_id'])) {
			$fieldSettings['field_id'] = $fieldSettings['grid_field_id'];
			$fieldSettings['is_grid'] = true;
		} elseif (isset($this->var_id)) {
			$fieldSettings['field_id'] = $this->var_id;
		} else {
			$fieldSettings['field_id'] = $this->field_id;
		}

		$fieldSettings['field_name'] = $this->field_name;

		if (isset($this->var_id)) {
			$fieldSettings['content_id'] = $this->var_id;
		} else {
			$fieldSettings['content_id'] = $this->content_id;
		}

		if (
			! $fieldSettings['content_id'] &&
			$entryId = ee()->input->get('entry_id', true)
		) {
			$fieldSettings['content_id'] = $entryId;
		}

		$fieldSettings['row_id'] = isset($fieldSettings['grid_row_id']) ?
			$fieldSettings['grid_row_id'] : null;

		if (isset($fieldSettings['blocks_atom_id'])) {
			$fieldSettings['content_type'] = 'blocks';
		} elseif (isset($fieldSettings['grid_field_id'])) {
			$fieldSettings['content_type'] = 'grid';
		} elseif (isset($this->var_id)) {
			$fieldSettings['content_type'] = 'low_vars';
		} else {
			$fieldSettings['content_type'] = 'channel';
		}

		if (isset($fieldSettings['channel_id'])) {
			$fieldSettings['source_id'] = $fieldSettings['channel_id'];
		} elseif (isset($this->var_id)) {
			$fieldSettings['source_id'] = $this->var_id;
		} else {
			$fieldSettings['source_id'] = ee()->input->get_post('channel_id');
		}

		// Set the source ID (really EE, come on, this is regression)
		// If var_id is set or Low Vars Grid
		if (
			isset($this->var_id) ||
			(
				isset($this->settings['grid_content_type']) &&
				$this->settings['grid_content_type'] === 'low_variables'
			)
		) {
			$fieldSettings['source_id'] = $fieldSettings['content_id'];

		// Otherwise we need to jump through hoops to figure out the source ID
		} else {
			// If content ID is set, we can use it to get source (channel) ID
			if ($fieldSettings['content_id']) {
				$content = ee('Model')->get('ChannelEntry')
					->filter('entry_id', $fieldSettings['content_id'])
					->first();

				$fieldSettings['source_id'] = (int) $content->channel_id;

			// Otherwise this is new entry and we can get the last seg argument
			} else {
				$fieldSettings['source_id'] = (int) end(ee()->uri->segments);
			}
		}

		if (! isset($fieldSettings['is_grid'])) {
			$fieldSettings['is_grid'] = false;
		}

		// And finally, return the damn settings. You really don't make this
		// easy, EE
		return $fieldSettings;
	}
}
