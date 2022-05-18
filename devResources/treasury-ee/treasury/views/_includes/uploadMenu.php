<fieldset class="tbl-search right">
	<?php if ($locationId): ?>
		<?php
			$uploadUrl = ee('CP/URL')->make('addons/settings/treasury/upload/' . $locationId);

			if ($modal) {
				$uploadUrl->setQueryStringVariable('modal', 'true');
			}
		?>
		<a href="<?=$uploadUrl?>" class="btn tn action">Upload New File</a>
	<?php elseif ($locationsCollection): ?>
		<div class="filters">
			<ul>
				<li>
					<a class="has-sub" href="">Upload New File</a>
					<div class="sub-menu">
						<ul>
							<?php foreach($locationsCollection as $location): ?>
								<?php
									$uploadUrl = ee('CP/URL')->make('addons/settings/treasury/upload/' . $location->id);

									if ($modal) {
										$uploadUrl->setQueryStringVariable('modal', 'true');
									}
								?>
								<li>
									<a class="add" href="<?=$uploadUrl?>">
										<?=$location->name?>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				</li>
			</ul>
		</div>
	<?php endif; ?>
</fieldset>
