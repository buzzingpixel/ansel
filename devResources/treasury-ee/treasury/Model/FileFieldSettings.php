<?php

namespace BuzzingPixel\Treasury\Model;

use BuzzingPixel\Treasury\Service\Data\Base;

class FileFieldSettings extends Base
{
	/**
	 * Model properties
	 */
	protected $location = 'string';
	protected $images_only = 'bool';
	protected $field_name = 'string';
	protected $field_required = 'bool';
	protected $is_grid = 'bool';
}
