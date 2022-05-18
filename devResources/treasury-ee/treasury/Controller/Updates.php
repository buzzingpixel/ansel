<?php

namespace BuzzingPixel\Treasury\Controller;

use BuzzingPixel\Treasury\Service\CP\Sidebar AS SidebarService;
use BuzzingPixel\Treasury\Service\CP\Assets as AssetsService;
use BuzzingPixel\Treasury\Service\Updates as UpdatesService;

class Updates
{
	/**
	 * Show the settings
	 */
	public function show()
	{
		// Set CP Assets
		AssetsService::set('editLocations');

		// Get the EE CP sidebar
		SidebarService::render();

		// Return heading, breadcrum, and body
		return array(
			'heading' => lang('updates'),
			'breadcrumb' => array(
				ee('CP/URL')->make('addons/settings/treasury')->compile() => lang('treasury')
			),
			'body' => ee('View')->make('treasury:updates')->render(array(
				'updatesFeed' => UpdatesService::getFeed(true)
			))
		);
	}
}
