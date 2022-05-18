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
	'explanation' => 'sftp_password_explanation',
	'inputName' => 'settings[password]',
	'inputType' => 'password',
	'inputValue' => $model->password
))?>

<?=$this->embed('treasury:_includes/fieldset', array(
	'title' => 'private_key',
	'explanation' => 'sftp_private_key_explanation',
	'inputName' => 'settings[private_key]',
	'inputType' => 'textarea',
	'inputValue' => $model->private_key
))?>

<?=$this->embed('treasury:_includes/fieldset', array(
	'title' => 'private_key_path',
	'explanation' => 'sftp_private_key_path_explanation',
	'inputName' => 'settings[private_key_path]',
	'inputType' => 'text',
	'inputValue' => $model->private_key_path
))?>

<?php if ($configPrivateKeyPath): ?>
	<?=$this->embed('treasury:_includes/fieldset', array(
		'title' => 'sftp_use_config_private_key_path',
		'explanation' => 'sftp_use_config_private_key_path_explanation',
		'inputName' => 'settings[use_config_private_key_path]',
		'inputType' => 'radio',
		'inputValue' => $model->use_config_private_key_path || ! $model->name ? 'y' : 'n',
		'choices' => array(
			array(
				'title' => 'yes',
				'value' => 'y'
			),
			array(
				'title' => 'no',
				'value' => 'n'
			)
		)
	))?>
<?php endif; ?>

<?=$this->embed('treasury:_includes/fieldset', array(
	'required' => true,
	'title' => 'port',
	'inputName' => 'settings[port]',
	'inputType' => 'text',
	'inputValue' => $model->port ?: 22
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
