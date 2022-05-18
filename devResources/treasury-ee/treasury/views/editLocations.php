<?php
	use BuzzingPixel\Treasury\Utility\Form;
?>

<div class="box">
	<h1>
		<?php if ($model->name): ?>
			<?=lang('edit_location')?>
		<?php else: ?>
			<?=lang('add_location')?>
		<?php endif; ?>
		<span class="req-title"><?=lang('required_fields')?></span>
	</h1>
	<?=Form::open($postUrl, array('class' => 'settings'))?>
		<?=ee('CP/Alert')->getAllInlines()?>

		<?=$this->embed('treasury:_includes/fieldset', array(
			'required' => true,
			'title' => 'name',
			'explanation' => 'name_explanation',
			'inputName' => 'name',
			'inputType' => 'text',
			'inputValue' => $model->name,
			'inputClass' => ! $model->handle ? 'js-auto-slug-title' : false
		))?>

		<?=$this->embed('treasury:_includes/fieldset', array(
			'required' => true,
			'title' => 'handle',
			'explanation' => 'handle_explanation',
			'inputName' => 'handle',
			'inputType' => 'text',
			'inputValue' => $model->handle,
			'inputClass' => ! $model->handle ? 'js-auto-slug-slug' : false
		))?>

		<?=$this->embed('treasury:_includes/fieldset', array(
			'required' => true,
			'title' => 'type',
			'explanation' => 'type_explanation',
			'inputClass' => 'js-type-selector',
			'inputName' => 'type',
			'inputType' => 'radio',
			'inputValue' => $model->type ?: 'local',
			'radioFullWidth' => true,
			'choices' => array(
				array(
					'title' => 'local',
					'value' => 'local'
				),
				array(
					'title' => 'amazon_s3',
					'value' => 'amazon_s3'
				),
				array(
					'title' => 'sftp',
					'value' => 'sftp'
				),
				array(
					'title' => 'ftp',
					'value' => 'ftp'
				)
			)
		))?>

		<div class="js-type-fields" data-current="local">
			<?php if ($model->type) : ?>
				<?=$this->embed("treasury:_includesLocationsTypesFields/{$model->type}")?>
			<?php else : ?>
				<?=$this->embed('treasury:_includesLocationsTypesFields/local')?>
			<?php endif; ?>
		</div>

		<?=$this->embed('treasury:_includes/fieldset', array(
			'last' => true,
			'required' => true,
			'title' => 'allowed_file_types',
			'explanation' => 'allowed_file_types_explanation',
			'inputName' => 'settings[allowed_file_types]',
			'inputType' => 'radio',
			'inputValue' => $model->allowed_file_types ?: 'images_only',
			'choices' => array(
				array(
					'title' => 'images_only',
					'value' => 'images_only'
				),
				array(
					'title' => 'all_file_types',
					'value' => 'all_file_types'
				)
			)
		))?>

		<?=$this->embed('treasury:_includes/fieldSetSubmit', array(
			'text' => 'save_button_text',
			'workText' => 'save_button_text_working'
		))?>

	<?=Form::close()?>
</div>

<script type="text/template" id="template__local__fields">
	<?=$this->embed('treasury:_includesLocationsTypesFields/local')?>
</script>

<script type="text/template" id="template__amazon_s3__fields">
	<?=$this->embed('treasury:_includesLocationsTypesFields/amazon_s3')?>
</script>

<script type="text/template" id="template__sftp__fields">
	<?=$this->embed('treasury:_includesLocationsTypesFields/sftp')?>
</script>

<script type="text/template" id="template__ftp__fields">
	<?=$this->embed('treasury:_includesLocationsTypesFields/ftp')?>
</script>
