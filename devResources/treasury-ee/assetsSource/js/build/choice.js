function runChoice() {
	$('.js-treasury-choice-wrapper').each(function() {
		var $el = $(this);

		$el.find('.js-treasury-choice-input').on('change', function() {
			var $input = $(this);

			$el.find('.js-treasury-choice-label').removeClass('chosen');

			$input.closest('.js-treasury-choice-label').addClass('chosen');
		});
	});
}

runChoice();
