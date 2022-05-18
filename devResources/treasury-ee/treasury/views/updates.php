<?php
	use BuzzingPixel\Treasury\Utility\UpdatesMarkdown;
?>

<div class="box">
	<h1><?=lang('treasury_updates')?></h1>
	<div class="treasury-updates">
		<?php foreach (array_slice($updatesFeed, 0, 4) as $update): ?>
			<div class="treasury-updates__item">
				<div class="treasury-updates__title-area">
					<?php if ($update['new']): ?>
						<a href="<?=$update['downloadUrl']?>" target="_blank" class="btn treasury-updates__download">Download</a>
					<?php endif; ?>
					<div class="treasury-updates__title"><?=$update['version']?></div>
					<div class="treasury-updates__released">Released <?=date_format(date_create($update['date']), 'n/j/Y')?></div>
					<span class="treasury-updates__status{% if update.new %} treasury-updates__status--new{% endif %}"><?php if ($update['new']): ?>new<?php else: ?>installed<?php endif; ?></span>
				</div>
				<div class="treasury-updates__notes">
					<?=UpdatesMarkdown::process($update['notes'])?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>
