<?=$this->embed('treasury:_includes/fieldset', array(
	'required' => true,
	'title' => 'access_key_id',
	'inputName' => 'settings[access_key_id]',
	'inputType' => 'text',
	'inputValue' => $model->access_key_id
))?>

<?=$this->embed('treasury:_includes/fieldset', array(
	'required' => true,
	'title' => 'secret_access_key',
	'inputName' => 'settings[secret_access_key]',
	'inputType' => 'text',
	'inputValue' => $model->secret_access_key
))?>

<?=$this->embed('treasury:_includes/fieldset', array(
	'required' => true,
	'title' => 'bucket_region',
	'inputName' => 'settings[bucket_region]',
	'inputType' => 'select',
	'inputValue' => $model->bucket_region,
	'options' => array(
		'us-east-1' => 'us-east-1',
		'us-west-1' => 'us-west-1',
		'us-west-2' => 'us-west-2',
		'eu-west-1' => 'eu-west-1',
		'eu-central-1' => 'eu-central-1',
		'ap-northeast-1' => 'ap-northeast-1',
		'ap-northeast-2' => 'ap-northeast-2',
		'ap-southeast-1' => 'ap-southeast-1',
		'ap-southeast-2' => 'ap-southeast-2',
		'sa-east-1' => 'sa-east-1'
	)
))?>

<?=$this->embed('treasury:_includes/fieldset', array(
	'required' => true,
	'title' => 'Bucket',
	'inputName' => 'settings[bucket]',
	'inputType' => 'text',
	'inputValue' => $model->bucket
))?>

<?=$this->embed('treasury:_includes/fieldset', array(
	'title' => 'subfolder',
	'explanation' => 'subfolder_explanation',
	'inputName' => 'settings[subfolder]',
	'inputType' => 'text',
	'inputValue' => $model->subfolder
))?>

<?=$this->embed('treasury:_includes/fieldset', array(
	'required' => true,
	'title' => 'location_url',
	'explanation' => 'location_url_explanation_s3',
	'inputName' => 'settings[url]',
	'inputType' => 'text',
	'inputValue' => $model->url
))?>
