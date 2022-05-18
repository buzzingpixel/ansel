<fieldset class="form-ctrls">
	<input
		type="submit"
		<?php if (isset($text)): ?>
		value="<?=lang($text)?>"
		<?php endif; ?>
		class="btn"
		<?php if (isset($text)): ?>
		data-submit-text="<?=lang($text)?>"
		<?php endif; ?>
		<?php if (isset($workText)): ?>
		data-work-text="<?=lang($workText)?>"
		<?php endif; ?>
	>
</fieldset>
