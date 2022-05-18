<?php
	use BuzzingPixel\Treasury\Utility\Form;
?>

<div class="box">
	<h1><?=$heading?><span class="req-title">Required Fields</span></h1>
	<?=Form::open($postUrl, array('class' => 'settings js-upload-file', 'enctype' => 'multipart/form-data', 'novalidate' => true), $params)?>
		<?=ee('CP/Alert')->getAllInlines()?>

		<?php if ($locationId): ?>
			<?=$this->embed('treasury:_includes/fieldset', array(
				'required' => true,
				'title' => 'file',
				'inputName' => 'file',
				'inputType' => 'file'
			))?>

			<?=$this->embed('treasury:_includes/fieldset', array(
				'title' => 'title',
				'inputName' => 'title',
				'inputType' => 'text'
			))?>

			<?=$this->embed('treasury:_includes/fieldset', array(
				'last' => true,
				'title' => 'description',
				'inputName' => 'description',
				'inputType' => 'textarea'
			))?>

			<?=$this->embed('treasury:_includes/fieldSetSubmit', array(
				'text' => 'upload_file',
				'workText' => 'upload_file_working'
			))?>
		<?php endif; ?>
	<?=Form::close()?>
</div>
