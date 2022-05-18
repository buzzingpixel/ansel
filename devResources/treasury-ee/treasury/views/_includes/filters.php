<?php
	// @codingStandardsIgnoreStart

	use BuzzingPixel\Treasury\Utility\Form;

	/** @var $totalFilesInLocation */
	/** @var $fileFilterOptions */
	/** @var $limitOptions */
	/** @var $pageUrl */
	/** @var $modal */
	/** @var $fileSearch */
	/** @var $pageMethod */

	$isEE6 = false;

	if (defined('APP_VER') &&
		version_compare(APP_VER, '6.0.0-b.1', '>=')
	) {
		$isEE6 = true;
	}
?>
<div class="filters<?php if ($isEE6): ?> treasury-filters<?php endif; ?>">
	<ul>
		<li
			<?php if ($isEE6): ?>
			class="treasury-filters__list-item"
			<?php endif; ?>
		>
			<a class="has-sub" href="" data-filter-label="show">
				<?php if ($totalFilesInLocation < $fileFilterOptions['limit']) : ?>
					show <span class="faded">(<?=$totalFilesInLocation?>)</span>
				<?php else : ?>
					show <span class="faded">(<?=$fileFilterOptions['limit']?>)</span>
				<?php endif; ?>
			</a>
			<?php if ($totalFilesInLocation > 10) : ?>
				<div class="sub-menu">
					<ul>
						<?php foreach ($limitOptions as $limitOption) : ?>
							<?php
								if ($totalFilesInLocation < $limitOption) {
									break;
								}
							?>
							<li>
								<?php
									$filterLink = ee('CP/URL')->make($pageUrl)
										->setQueryStringVariable('limit', $fileFilterOptions['limit'])
										->setQueryStringVariable('order', $fileFilterOptions['order'])
										->setQueryStringVariable('sort', $fileFilterOptions['sort'])
										->setQueryStringVariable('limit', $limitOption);

									if ($modal) {
										$filterLink->setQueryStringVariable('modal', 'true');
									}

									if ($fileSearch) {
										$filterLink->setQueryStringVariable('search', $fileSearch);
									}
								?>
								<a href="<?=$filterLink?>">
									<?=$limitOption?> results
								</a>
							</li>
						<?php endforeach; ?>
						<?php if ($totalFilesInLocation > $limitOptions[0]) : ?>
							<?php
								$url = ee('CP/URL')->make($pageUrl)->setQueryStringVariable('limit', $totalFilesInLocation);

								if ($modal) {
									$url->setQueryStringVariable('modal', 'true');
								}

								if ($fileSearch) {
									$url->setQueryStringVariable('search', $fileSearch);
								}
							?>
							<li>
								<a href="<?=$url?>">
									All <?=$totalFilesInLocation?> files
								</a>
							</li>
						<?php endif; ?>
					</ul>
				</div>
			<?php endif; ?>
		</li>
		<li
			<?php if ($isEE6): ?>
			class="treasury-filters__list-item"
			<?php endif; ?>
		>
			<?php
				$args = array(
					'limit' => $fileFilterOptions['limit']
				);

				if ($modal) {
					$args['modal'] = 'true';
				}
			?>
			<?php
				$formClasses = 'js-file-search';
				if ($isEE6) {
					$formClasses .= ' treasury-filters__search-form';
				}
			?>
			<?=Form::open($pageMethod, array('class' => 'js-file-search'), $args)?>
				<input
					placeholder="search&hellip;"
					type="search"
					name="search"
					value="<?=$fileSearch?>"
					<?php if ($isEE6): ?>
					class="treasury-filters__search-input"
					<?php endif; ?>
				>
				<input class="btn submit<?php if ($isEE6): ?> treasury-filters__search-submit<?php endif; ?>" type="submit" value="Search Files">
			<?=Form::close()?>
		</li>
	</ul>
</div>
