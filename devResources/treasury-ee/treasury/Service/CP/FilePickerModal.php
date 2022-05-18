<?php

namespace BuzzingPixel\Treasury\Service\CP;

class FilePickerModal
{
	/**
	 * Set FilePickerModal
	 */
	public static function set()
	{
		// If we’ve already set the modal there’s nothing to do here
		if (ee()->session->cache('treasury', 'filePickerModalSet')) {
			return;
		}

		// Get the modal HTML
		$modalHtml = ee('View')->make('ee:_shared/modal')->render(array(
			'name' => 'js-treasury-file-picker-modal',
			'contents' => ''
		));

		// Add the modal
		ee('CP/Modal')->addModal('js-treasury-file-picker-modal', $modalHtml);
	}
}
