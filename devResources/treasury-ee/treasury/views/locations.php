<?php
	// @codingStandardsIgnoreStart

	use \BuzzingPixel\Treasury\Utility\Form;

	/** @var $heading */
	/** @var $modal */
	/** @var $pageUrl */
	/** @var $files */
	/** @var $fileFilterOptions */

	$isEE6 = false;

	if (defined('APP_VER') &&
		version_compare(APP_VER, '6.0.0-b.1', '>=')
	) {
		$isEE6 = true;
	}
?>

<div class="box">
	<div class="tbl-ctrls">
		<?=$this->embed('treasury:_includes/uploadMenu')?>
		<h1><?=$heading?></h1>
			<input type="hidden" name="return" value="<?=$pageUrl?>">
			<?=ee('CP/Alert')->getAllInlines()?>
			<div class="treasury-filters-bulk-top<?php if ($isEE6): ?> treasury-filters-bulk-top--is-ee-6<?php endif; ?>">
				<?php if (! $modal) : ?>
					<?=$this->embed('treasury:_includes/bulkActions', array(
						'buttonClass' => 'js-top-blk-action-btn'
					))?>
				<?php endif; ?>
				<?=$this->embed('treasury:_includes/filters')?>
			</div>
			<?php if (! $modal) : ?>
			<?=Form::open('deleteFiles', array('class' => 'js-bulk-actions-form'))?>
			<?php endif; ?>
			<div class="tbl-wrap" style="width: 100%;">
				<table class="treasury-listing-table">
					<thead>
						<tr>
							<th class="treasury-listing-table__preview-col">
								Preview
							</th>
							<th class="treasury-listing-table__title-col<?php if ($fileFilterOptions['order'] === 'title'): ?> highlight<?php endif; ?>">
								Title/Name
								<?php
									$titleSortLink = ee('CP/URL')->make($pageUrl)
										->setQueryStringVariable('limit', $fileFilterOptions['limit'])
										->setQueryStringVariable('order', 'title');

									if ($fileFilterOptions['order'] !== 'title' || ($fileFilterOptions['order'] === 'title' && $fileFilterOptions['sort'] === 'asc')) {
										$titleSortLink->setQueryStringVariable('sort', 'desc');
									} else {
										$titleSortLink->setQueryStringVariable('sort', 'asc');
									}

									if ($modal) {
										$titleSortLink->setQueryStringVariable('modal', 'true');
									}
								?>
								<a href="<?=$titleSortLink?>" class="sort <?php if ($fileFilterOptions['order'] === 'title' && $fileFilterOptions['sort'] === 'asc'): ?> asc<?php else: ?> desc<?php endif; ?>"></a>
							</th>
							<th class="treasury-listing-table__type-col<?php if ($fileFilterOptions['order'] === 'mime_type'): ?> highlight<?php endif; ?>">
								File Type
								<?php
									$typeSortLink = ee('CP/URL')->make($pageUrl)
										->setQueryStringVariable('limit', $fileFilterOptions['limit'])
										->setQueryStringVariable('order', 'mime_type');

									if ($fileFilterOptions['order'] !== 'mime_type' || ($fileFilterOptions['order'] === 'mime_type' && $fileFilterOptions['sort'] === 'asc')) {
										$typeSortLink->setQueryStringVariable('sort', 'desc');
									} else {
										$typeSortLink->setQueryStringVariable('sort', 'asc');
									}

									if ($modal) {
										$typeSortLink->setQueryStringVariable('modal', 'true');
									}
								?>
								<a href="<?=$typeSortLink?>" class="sort  <?php if ($fileFilterOptions['order'] === 'mime_type' && $fileFilterOptions['sort'] === 'asc'): ?> asc<?php else: ?> desc<?php endif; ?>"></a>
							</th>
							<th class="treasury-listing-table__date-col<?php if ($fileFilterOptions['order'] === 'upload_date'): ?> highlight<?php endif; ?>">
								Date Added
								<?php
									$dateSortLink = ee('CP/URL')->make($pageUrl)
										->setQueryStringVariable('limit', $fileFilterOptions['limit'])
										->setQueryStringVariable('order', 'upload_date');

									if ($fileFilterOptions['order'] !== 'upload_date' || ($fileFilterOptions['order'] === 'upload_date' && $fileFilterOptions['sort'] === 'asc')) {
										$dateSortLink->setQueryStringVariable('sort', 'desc');
									} else {
										$dateSortLink->setQueryStringVariable('sort', 'asc');
									}

									if ($modal) {
										$dateSortLink->setQueryStringVariable('modal', 'true');
									}
								?>
								<a href="<?=$dateSortLink?>" class="sort <?php if ($fileFilterOptions['order'] === 'upload_date' && $fileFilterOptions['sort'] === 'asc'): ?> asc<?php else: ?> desc<?php endif; ?>"></a>
							</th>
							<?php if (! $modal): ?>
								<th class="treasury-listing-table__manage-col">
									Manage
								</th>
							<?php endif; ?>
							<th class="treasury-listing-table__select-col"></th>
						</tr>
					</thead>
					<tbody>
						<?php if (! count($files)): ?>
							<tr class="no-results">
								<td class="solo" colspan="5">
									No Files Found
								</td>
							</tr>
						<?php endif; ?>
						<?php foreach ($files as $key => $file): ?>
							<tr class="js-file-row" data-file-json-href="<?=ee('CP/URL')->make('addons/settings/treasury/fileJson/' . $file->id)?>">
								<td class="treasury-listing-table__preview-col">
									<?php if (! $modal): ?>
									<a href="<?=ee('CP/URL')->make('addons/settings/treasury/editFile/' . $file->id)?>">
									<?php endif; ?>
										<img
											src="<?=$file->thumb_url?>"
											alt="<?=$file->title?>"
										>
									<?php if (! $modal): ?>
									</a>
									<?php endif; ?>
								</td>
								<td class="treasury-listing-table__title-col">
									<?php if (! $modal): ?>
									<a href="<?=ee('CP/URL')->make('addons/settings/treasury/editFile/' . $file->id)?>">
									<?php endif; ?>
										<?=$file->title?>
									<?php if (! $modal): ?>
									</a>
									<?php endif; ?>
								</td>
								<td class="treasury-listing-table__type-col">
									<?=$file->mime_type?>
								</td>
								<td class="treasury-listing-table__date-col">
									<?=$file->upload_date()?>
								</td>
								<?php if (! $modal): ?>
									<td class="treasury-listing-table__manage-col">
										<ul class="toolbar">
											<?php if ($file->is_image): ?>
												<li class="view">
													<a
														href="<?=$file->file_url?>"
														target="_blank"
														title="View"
														data-file-id="<?=$file->id?>"
													></a>
												</li>
											<?php endif; ?>
											<li class="edit">
												<a
													href="<?=ee('CP/URL')->make('addons/settings/treasury/editFile/' . $file->id)?>"
													title="Edit"
												></a>
											</li>
											<li class="download">
												<a
													href="<?=$file->file_url?>"
													title="Download"
													download
												></a>
											</li>
										</ul>
									</td>
								<?php endif; ?>
								<td class="treasury-listing-table__select-col">
									<input
										name="selection[]"
										value="<?=$file->id?>"
										type="checkbox"
										class="js-image-bulk-select"
									>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<div class="treasury-pagination-wrap">
				<?=$pagination?>
			</div>
			<?php if (! $modal): ?>
				<div class="treasury-filters-bulk-bottom<?php if ($isEE6): ?> treasury-filters-bulk-bottom--is-ee-6<?php endif; ?>">
					<?=$this->embed('treasury:_includes/bulkActions')?>
				</div>
			<?php endif; ?>
			<?php if (! $modal): ?>
				<?=Form::close()?>
			<?php endif; ?>
	</div>
</div>
