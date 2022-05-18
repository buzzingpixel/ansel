<?php
// @codingStandardsIgnoreStart
$isGTEE3 = version_compare(APP_VER, '3.99.99', '>');
?>

<fieldset class="col-group<?php if (isset($required) && $required): ?> required<?php endif; ?><?php if (isset($last) && $last): ?> last<?php endif; ?>">
	<?php if (isset($title)): ?>
		<div class="setting-txt col w-7">
			<h3><?=lang($title)?></h3>
			<?php if (isset($explanation)): ?>
				<em><?=lang($explanation)?></em>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<div class="setting-field col w-9 last">
		<?php if (isset($inputType)): ?>
			<?php if ($inputType === 'text' || $inputType === 'password'): ?>
				<input
					type="<?=$inputType?>"
					<?php if (isset($inputName)): ?>
					name="<?=$inputName?>"
					<?php endif; ?>
					<?php if (isset($inputValue)): ?>
					value="<?=$inputValue?>"
					<?php endif; ?>
					<?php if (isset($inputClass)): ?>
					class="<?=$inputClass?>"
					<?php endif; ?>
					<?php if (isset($disabled) && $disabled): ?>
					disabled
					<?php endif; ?>
					<?php if (isset($required) && $required): ?>
					required
					<?php endif; ?>
				>
			<?php elseif ($inputType === 'radio'): ?>
				<?php if (isset($choices) && gettype($choices) === 'array'): ?>
					<?php if ($isGTEE3) : ?><div class="js-treasury-choice-wrapper"><?php endif; ?>
						<?php
							$counter = 1;
							$total = count($choices);
						?>
						<?php foreach ($choices as $choice): ?>
							<label
								class="
									js-treasury-choice-label
									choice
									<?php if (isset($radioFullWidth) && $radioFullWidth): ?>
									block
									<?php elseif ($counter !== $total): ?>
									mr
									<?php endif; ?>
									<?php if (isset($inputValue) && $inputValue === $choice['value']): ?>
									chosen
									<?php endif; ?>"
							>
								<input
									type="radio"
									<?php if (isset($inputName)): ?>
									name="<?=$inputName?>"
									<?php endif; ?>
									<?php if (isset($choice['value'])): ?>
									value="<?=$choice['value']?>"
									<?php endif; ?>
									class="<?php if (isset($inputClass)): ?><?=$inputClass?> <?php endif; ?>js-treasury-choice-input"
									<?php if (isset($inputValue) && $inputValue === $choice['value']): ?>
									checked
									<?php endif; ?>
									<?php if (isset($disabled) && $disabled): ?>
									disabled
									<?php endif; ?>
								>
								<?php if (isset($choice['title'])): ?>
									<?=lang($choice['title'])?>
								<?php endif; ?>
							</label>
							<?php
								$counter++;
							?>
						<?php endforeach; ?>
					<?php if ($isGTEE3) : ?></div><?php endif; ?>
				<?php endif; ?>
			<?php elseif ($inputType === 'select'): ?>
				<select
					<?php if (isset($inputName)): ?>
					name="<?=$inputName?>"
					<?php endif; ?>
					<?php if (isset($inputClass)): ?>
					class="<?=$inputClass?>"
					<?php endif; ?>
					<?php if (isset($disabled) && $disabled): ?>
					disabled
					<?php endif; ?>
				>
					<?php if (isset($options) && gettype($options) === 'array'): ?>
						<?php foreach ($options as $value => $option): ?>
							<option
								value="<?=$value?>"
								<?php if (isset($inputValue) && $inputValue === $value): ?>
								selected
								<?php endif; ?>
							>
								<?=lang($option)?>
							</option>
						<?php endforeach; ?>
					<?php endif; ?>
				</select>
			<?php elseif ($inputType === 'file'): ?>
				<input
					type="file"
					<?php if (isset($inputName)): ?>
					name="<?=$inputName?>"
					<?php endif; ?>
					<?php if (isset($required) && $required): ?>
					required
					<?php endif; ?>
				>
			<?php elseif ($inputType === 'textarea'): ?>
				<textarea
					<?php if (isset($inputName)): ?>
					name="<?=$inputName?>"
					<?php endif; ?>
					<?php if (isset($textareaCols)): ?>
					cols="<?=$textareaCols?>"
					<?php endif; ?>
					<?php if (isset($textareaRows)): ?>
					rows="<?=$textareaRows?>"
					<?php endif; ?>
					<?php if (isset($required) && $required): ?>
					required
					<?php endif; ?>
				><?php if (isset($inputValue)): ?><?=$inputValue?><?php endif; ?></textarea>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</fieldset>
