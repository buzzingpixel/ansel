<?=$this->embed('treasury:_includes/fieldset', array(
	'required' => true,
	'title' => 'location_url',
	'explanation' => 'location_url_explanation',
	'inputName' => 'settings[url]',
	'inputType' => 'text',
	'inputValue' => $model->url
))?>

<?=$this->embed('treasury:_includes/fieldset', array(
	'required' => true,
	'title' => 'location_path',
	'explanation' => lang('location_path_explanation') . '<br>' . lang('location_path_explanation_document_root') . '<br><code>' . $_SERVER['DOCUMENT_ROOT'] . '</code>',
	'inputName' => 'settings[path]',
	'inputType' => 'text',
	'inputValue' => $model->path
))?>
