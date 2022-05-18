<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

use BuzzingPixel\Treasury\Controller\FilesTag;

class Treasury
{
	/**
	 * Files tag
	 */
	public function files()
	{
		// Get the FilesTag controller and render
		$controller = new FilesTag(ee()->TMPL->tagparams, ee()->TMPL->tagdata);
		return $controller->render();
	}
}
