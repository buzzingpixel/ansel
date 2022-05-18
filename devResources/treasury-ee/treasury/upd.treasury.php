<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

use BuzzingPixel\Treasury\Controller\Installer;

class Treasury_upd
{
	public $name = TREASURY_NAME;
	public $version = TREASURY_VER;

	/**
	 * Install
	 *
	 * @return bool
	 */
	public function install()
	{
		Installer::install();
		return true;
	}

	/**
	 * Uninstall
	 *
	 * @return bool
	 */
	public function uninstall()
	{
		Installer::uninstall();
		return true;
	}

	/**
	 * Update
	 *
	 * @param string $current The current version before update
	 * @return bool
	 */
	public function update($current = '')
	{
		// Get add-on info
		$addonInfo = ee('Addon')->get('treasury');

		// Check if updating is needed
		if ($current === $addonInfo->get('version')) {
			return false;
		}

		// Check if we need to install the extension
		if (version_compare($current, '1.0.0-b.1', '<')) {
			// Add extension
			\BuzzingPixel\Treasury\Service\Install\ExtensionInstaller::add();
		}

		// Check if we need to install the settings table
		if (version_compare($current, '1.0.0-rc.1', '<')) {
			// Add settings table
			\BuzzingPixel\Treasury\Service\Install\SettingsInstaller::add();
		}

		// Run general update routines
		Installer::generalUpdate();

		// All done
		return true;
	}
}
