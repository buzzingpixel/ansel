// js-image-bulk-delete
(function(F) {
	'use strict';

	F.fn.make('imageBulkDelete', {
		/**
		 * Initialize image build delete
		 */
		init: function() {
			$('body').on('change', '.js-image-bulk-select', function() {
				var anySelected = false;

				$('.js-image-bulk-select').each(function() {
					if (this.checked) {
						anySelected = true;
						return false;
					}
				});

				if (anySelected) {
					$('.js-image-bulk-actions').jsShow();
				} else {
					$('.js-image-bulk-actions').jsHide();
				}
			});
		}
	});
})(window.TREASURY);
