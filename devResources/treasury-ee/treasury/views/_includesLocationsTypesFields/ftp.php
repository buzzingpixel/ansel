<?=$this->embed('treasury:_includes/fieldset', array(
	'required' => true,
	'title' => 'server',
	'inputName' => 'settings[server]',
	'inputType' => 'text',
	'inputValue' => $model->server
))?>

<?=$this->embed('treasury:_includes/fieldset', array(
	'required' => true,
	'title' => 'username',
	'inputName' => 'settings[username]',
	'inputType' => 'text',
	'inputValue' => $model->username
))?>

<?=$this->embed('treasury:_includes/fieldset', array(
	'title' => 'password',
	'inputName' => 'settings[password]',
	'inputType' => 'password',
	'inputValue' => $model->password
))?>

<?=$this->embed('treasury:_includes/fieldset', array(
	'required' => true,
	'title' => 'port',
	'inputName' => 'settings[port]',
	'inputType' => 'text',
	'inputValue' => $model->port ?: 21
))?>

<?=$this->embed('treasury:_includes/fieldset', array(
	'title' => 'remote_path',
	'inputName' => 'settings[remote_path]',
	'inputType' => 'text',
	'inputValue' => $model->remote_path
))?>

<?=$this->embed('treasury:_includes/fieldset', array(
	'required' => true,
	'title' => 'location_url',
	'explanation' => 'location_url_explanation_sftp',
	'inputName' => 'settings[url]',
	'inputType' => 'text',
	'inputValue' => $model->url
))?>
