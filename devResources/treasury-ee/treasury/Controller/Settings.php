<?php

namespace BuzzingPixel\Treasury\Controller;

use BuzzingPixel\Treasury\Service\CP\Sidebar as SidebarService;
use BuzzingPixel\Treasury\Service\CP\Assets as AssetsService;
use BuzzingPixel\Treasury\Service\Settings as SettingsService;

class Settings
{
	private $saveData;

	/**
	 * Set post data
	 *
	 * @param array $data
	 * @return self
	 */
	public function setPostData($data)
	{
		// Make sure incoming data is an array
		if (gettype($data) !== 'array') {
			return $this;
		}

		// Set class saveData as an array
		$this->saveData = array();

		// If enable_menu is set, add it to the saveData array
		if (isset($data['settings']['enable_menu'])) {
			$this->saveData['enable_menu'] = $data['settings']['enable_menu'];
		}

		// If license_key is set, add it to the saveData array
		if (isset($data['settings']['license_key'])) {
			$this->saveData['license_key'] = $data['settings']['license_key'];
		}

		// Return this class
		return $this;
	}

	/**
	 * Show the settings
	 */
	public function show()
	{
		// Set CP Assets
		AssetsService::set('settings');

		// Get the EE CP sidebar
		SidebarService::render();

		// Get the extension
		$extension = ee('Model')->get('Extension')
			->filter('class', 'Treasury_ext')
			->first();

		// Return heading, breadcrum, and body
		return array(
			'heading' => lang('settings'),
			'breadcrumb' => array(
				ee('CP/URL')->make('addons/settings/treasury')->compile() => lang('treasury')
			),
			'body' => ee('View')->make('treasury:settings')->render(array(
				'extensionEnabled' => $extension->enabled,
				'settings' => SettingsService::getInstance()
			))
		);
	}

	/**
	 * Save settings
	 */
	public function save()
	{
		// Get the extension
		$extension = ee('Model')->get('Extension')
			->filter('class', 'Treasury_ext')
			->first();

		// Set extension enabled
		if (
			isset($this->saveData['enable_menu']) &&
			$this->saveData['enable_menu'] === 'y'
		) {
			$extension->enabled = true;
		} else {
			$extension->enabled = false;
		}

		// Save the extension
		$extension->save();

		// Set license key
		if (isset($this->saveData['license_key'])) {
			// Get the setttings
			$settings = SettingsService::getInstance();

			// Add the key
			$settings->license_key = ee('Security/XSS')->clean(
				$this->saveData['license_key']
			);

			// Save the settings
			$settings->save();
		}

		// Show the success message
		ee('CP/Alert')->makeInline('treasury_settings_saved')
			->asSuccess()
			->canClose()
			->withTitle(lang('treasury_settings_saved_title'))
			->addToBody(lang('treasury_settings_saved_body'))
			->defer();

		// Redirect to this page
		ee()->functions->redirect(
			ee(
				'CP/URL',
				"addons/settings/treasury/settings"
			)
		);
	}
}
