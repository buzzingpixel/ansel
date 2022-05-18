(function(F) {
	'use strict';

	F.fn.make('addLocationType', {
		// The type selector
		$typeSelector: null,
		// The fields container
		$typeFields: null,

		/**
		 * Initialize location type switching
		 */
		init: function() {
			// Save a reference to the function set
			var self = this;

			// Save the selector
			self.$typeSelector = $('.js-type-selector');

			// Save the type fields container
			self.$typeFields = $('.js-type-fields');

			// Set up the model
			self.set('current', self.$typeFields.data('current'));

			// Set up type change event
			self.$typeSelector.on('change', function() {
				self.set('current', this.value);
				self.$typeFields.data('current', this.value);
			});

			// Set up model change watcher
			self.on('change:current', function(val) {
				self.$typeFields.html(
					$('#template__' + val + '__fields').html()
				);
			});
		}
	});
})(window.TREASURY);
