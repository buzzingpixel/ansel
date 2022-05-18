<?php
	use BuzzingPixel\Treasury\Utility\Form;
	$splitLeft = 'w-7';
	$splitRight = 'w-9';
?>

<div class="box">

	<? /* Page title */ ?>
	<h1><?=lang('settings')?></h1>

	<?=Form::open('settings', array('class' => 'settings'))?>

		<? /* Get inline CP alerts */ ?>
		<?=ee('CP/Alert')->getAllInlines()?>

		<? /* Enable Menu */ ?>
		<?php if (version_compare(APP_VER, '3.4.0', '>=')): ?>
			<?=$this->embed('treasury:_includes/fieldset', array(
				'title' => 'enable_menu',
				'inputName' => 'settings[enable_menu]',
				'inputType' => 'radio',
				'inputValue' => $extensionEnabled ? 'y' : 'n',
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

		<? /* License Key */ ?>
		<?=$this->embed('treasury:_includes/fieldset', array(
			'last' => true,
			'title' => 'devotee_license_key',
			'inputName' => 'settings[license_key]',
			'inputType' => 'text',
			'inputValue' => $settings->license_key
		))?>

		<? /* Save button */ ?>
		<?=$this->embed('treasury:_includes/fieldSetSubmit', array(
			'text' => 'save_button_text',
			'workText' => 'save_button_text_working'
		))?>

	<?=Form::close()?>

</div>
