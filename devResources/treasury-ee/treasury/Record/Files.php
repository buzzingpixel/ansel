<?php

namespace BuzzingPixel\Treasury\Record;

use BuzzingPixel\Treasury\Service\Data\Base;

class Files extends Base
{
	// Source table name
	public static $_tableName = 'treasury_files';

	// Model properties
	protected $site_id = 'int';
	protected $title = array(
		'type' => 'string',
		'db' => array(
			'null' => false,
			'type' => 'VARCHAR',
			'constraint' => 255
		)
	);
	protected $location_id = 'int';
	protected $is_image = 'bool';
	protected $mime_type = array(
		'type' => 'string',
		'db' => array(
			'null' => false,
			'type' => 'VARCHAR',
			'constraint' => 255
		)
	);
	protected $file_name = array(
		'type' => 'string',
		'db' => array(
			'null' => false,
			'type' => 'VARCHAR',
			'constraint' => 255
		)
	);
	protected $extension = array(
		'type' => 'string',
		'db' => array(
			'null' => false,
			'type' => 'VARCHAR',
			'constraint' => 12
		)
	);
	protected $file_size = array(
		'type' => 'string',
		'db' => array(
			'null' => false,
			'type' => 'VARCHAR',
			'constraint' => 255
		)
	);
	protected $description = 'string';
	protected $uploaded_by_member_id = 'int';
	protected $upload_date = 'int';
	protected $modified_by_member_id = 'int';
	protected $modified_date = 'int';
	protected $height = 'int';
	protected $width = 'int';
}
