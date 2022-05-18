<?php

namespace BuzzingPixel\Treasury\Model;

use BuzzingPixel\Treasury\Service\Data\BaseParams;

class FilesTagparams extends BaseParams
{
	protected $id = 'intArray';
	protected $id_operator = 'string';

	protected $location_id = 'intArray';
	protected $location_id_operator = 'string';

	protected $site_id = 'intArray';
	protected $site_id_operator = 'string';

	protected $file_name = 'stringArray';
	protected $file_name_operator = 'string';

	protected $uploaded_by_member_id = 'intArray';
	protected $uploaded_by_member_id_operator = 'string';

	protected $modified_by_member_id = 'intArray';
	protected $modified_by_member_id_operator = 'string';

	protected $width = 'intArray';
	protected $width_operator = 'string';

	protected $height = 'intArray';
	protected $height_operator = 'string';

	protected $limit = 'int';

	protected $offset = 'int';

	protected $order = 'int';

	protected $sort = 'string';

	protected $namespace = 'string';

	public static $filters = array(
		'id' => 'IN',
		'location_id' => 'IN',
		'site_id' => 'IN',
		'file_name' => 'IN',
		'uploaded_by_member_id' => 'IN',
		'modified_by_member_id' => 'IN',
		'width' => 'IN',
		'height' => 'IN'
	);

	/**
	 * Set default site id
	 */
	protected function _namespace_default()
	{
		return 'treasury';
	}
}
