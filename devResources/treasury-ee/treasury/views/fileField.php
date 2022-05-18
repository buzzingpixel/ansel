<div class="treasury-file-field js-treasury-file-field<?php if ($settings->is_grid): ?> js-is-grid<?php endif; ?>">
	<div class="treasury-file-field__input-wrapper">
		<input
			type="hidden"
			name="<?=$settings->field_name?>"
			value="<?=$fileModel->id?>"
			class="js-treasury-file-input"
		>
		<div class="treasury-file-field__image-thumb<?php if (! $fileModel->id): ?> js-hide<?php endif; ?> js-thumb-wrapper">
			<span class="treasury-close-btn treasury-file-field__thumb-delete js-thumb-delete"></span>
			<div class="js-image-thumb">
				<?php if ($fileModel->id): ?>
					<img src="<?=$fileModel->thumb_url?>">
				<?php endif; ?>
			</div>
		</div>
		<div class="treasury-file-field__file-btn-wrapper">
			<?=$filePickerLink?>
		</div>
	</div>
</div>
