(function(F, G, FLUID) {
	'use strict';

	F.fn.make('fileFieldInit', {
		/**
		 * Initialize
		 */
		init: function() {
			// Loop through non-grid fields and initialize
			$('.js-treasury-file-field').not('.js-is-grid').each(function() {
				F.fn.clone('fileField', true, $(this));
			});

			// Bind grid fields
			G.bind('treasury_file', 'display', function($cell) {
				$cell.find('.js-treasury-file-field').each(function() {
					F.fn.clone('fileField', true, $(this));
				});
			});

			// Bind fluid fields
			if (FLUID !== undefined) {
				FLUID.on('treasury_file', 'add', function($row) {
					$row.find('.js-treasury-file-field').each(function() {
						F.fn.clone('fileField', true, $(this));
					});
				});
			}
		}
	});
})(window.TREASURY, window.Grid, window.FluidField);
