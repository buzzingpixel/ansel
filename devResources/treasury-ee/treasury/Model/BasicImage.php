<?php

namespace BuzzingPixel\Treasury\Model;

use BuzzingPixel\Treasury\Service\Data\Base;

class BasicImage extends Base
{
	// Model properties
	protected $location = 'string';
	protected $filename = 'string';
	protected $width = 'int';
	protected $height = 'int';
}
