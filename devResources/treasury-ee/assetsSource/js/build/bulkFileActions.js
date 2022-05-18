(function(F) {
	'use strict';

	F.fn.make('bulkFileActions', {
		/**
		 * Auto initilize this set on every page load
		 */
		autoInit: true,

		/**
		 * Tries
		 */
		tries: 0,

		/**
		 * Initialize location type switching
		 */
		init: function() {
			// Watch for changes to bulk actions selects
			$('body').on('change', '.js-treasury-blk-action', function() {
				var $el = $(this);

				$('.js-treasury-blk-action').not($el).val($el.val());
			});

			// Watch for clicks on the button not in the form
			$('body').on('click', '.js-top-blk-action-btn', function() {
				$('.js-bulk-actions-form').submit();
			});
		}
	});
})(window.TREASURY);
