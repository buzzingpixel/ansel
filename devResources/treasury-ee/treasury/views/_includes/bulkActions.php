<?php
	use BuzzingPixel\Treasury\Utility\Form;

	$isEE6 = false;

	if (defined('APP_VER') &&
		version_compare(APP_VER, '6.0.0-b.1', '>=')
	) {
		$isEE6 = true;
	}
?>

<fieldset
	class="tbl-bulk-act js-image-bulk-actions js-hide<?php if ($isEE6): ?> treasury-blk-actions<?php endif; ?>"
>
	<select name="bulk_action" class="js-treasury-blk-action">
		<option value="">-- with selected --</option>
		<option value="remove">Remove</option>
	</select>
	<button class="btn submit<?php if (isset($buttonClass)): ?> <?=$buttonClass?><?php endif; ?>">Submit</button>
</fieldset>
