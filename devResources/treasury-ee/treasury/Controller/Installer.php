<?php

namespace BuzzingPixel\Treasury\Controller;

use BuzzingPixel\Treasury\Service\Install\SettingsInstaller;
use BuzzingPixel\Treasury\Service\Install\ModuleInstaller;
use BuzzingPixel\Treasury\Service\Install\RecordInstaller;
use BuzzingPixel\Treasury\Service\Install\ExtensionInstaller;

class Installer
{
	/**
	 * Install
	 */
	public static function install()
	{
		// Install settings
		SettingsInstaller::add();

		// Install Locations record
		RecordInstaller::install('Locations');

		// Install Files record
		RecordInstaller::install('Files');

		// Add module
		ModuleInstaller::add();

		// Add extension
		ExtensionInstaller::add();
	}

	/**
	 * Uninstall
	 */
	public static function uninstall()
	{
		// Install settings
		SettingsInstaller::remove();

		// Uninstall Locations record
		RecordInstaller::uninstall('Locations');

		// Uninstall Files record
		RecordInstaller::uninstall('Files');

		// Remove module
		ModuleInstaller::remove();

		// Remove extension
		ExtensionInstaller::remove();
	}

	/**
	 * General update routines
	 */
	public static function generalUpdate()
	{
		// Make sure record DB columns are up to date
		RecordInstaller::checkAndUpdateColumns('Locations');
		RecordInstaller::checkAndUpdateColumns('Files');

		// Update module
		ModuleInstaller::update();

		// Update extension
		ExtensionInstaller::update();
	}
}
