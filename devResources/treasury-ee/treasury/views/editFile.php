<?php
	use BuzzingPixel\Treasury\Utility\Form;
?>

<div class="box edit-file">
	<h1><?=$heading?><?php if ($fileModel): ?><span class="req-title">Required Fields</span><?php endif; ?></h1>
	<?=Form::open($postUrl, array('class' => 'settings', 'enctype' => 'multipart/form-data'))?>
		<?=ee('CP/Alert')->getAllInlines()?>

		<?php if ($fileModel): ?>
			<fieldset class="col-group">
				<div class="setting-txt col w-7">
					<h3><?=lang('preview')?></h3>
				</div>
				<div class="setting-field col w-9 last">
					<div class="edit-file__preview-container">
						<?php if ($fileModel->is_image): ?>
							<img src="<?=$fileModel->file_url?>" alt="<?=$fileModel->title?>">
						<?php else: ?>
							<img src="<?=$fileModel->thumb_url?>" alt="<?=$fileModel->title?>">
							<br>
							<br>
							<?=$fileModel->title?>
						<?php endif; ?>
					</div>
				</div>
			</fieldset>

			<?=$this->embed('treasury:_includes/fieldset', array(
				'required' => true,
				'title' => 'title',
				'inputName' => 'title',
				'inputType' => 'text',
				'inputValue' => $fileModel->title
			))?>

			<?=$this->embed('treasury:_includes/fieldset', array(
				'last' => true,
				'title' => 'description',
				'inputName' => 'description',
				'inputType' => 'textarea',
				'inputValue' => $fileModel->description
			))?>

			<?=$this->embed('treasury:_includes/fieldSetSubmit', array(
				'text' => 'save_file',
				'workText' => 'save_file_working'
			))?>
		<?php endif; ?>
	<?=Form::close()?>
</div>
