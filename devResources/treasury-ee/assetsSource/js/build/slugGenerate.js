(function(F) {
	'use strict';

	F.fn.make('slugGenerate', {
		init: function() {
			// Save a reference to the function set
			var self = this;
			// Get the title
			var $title = $('.js-auto-slug-title');
			// Get the slug
			var $slug = $('.js-auto-slug-slug');

			// Check if we have anything to do
			if (! $title.length && ! $slug.length) {
				return;
			}

			$title.on('keyup change focusout', function() {
				$slug.val(self.slugify($title.val()));
			});
		},

		/**
		 * Slugify
		 *
		 * @param {string} text
		 * @return {string}
		 */
		slugify: function(text) {
			// jscs:disable
			return text
				.toString()                 // Make sure it's a string
				.toLowerCase()              // Make it all lower case
				.replace(/\s+/g, '-')       // Replace spaces with -
				.replace(/[^\w\-]+/g, '')   // Remove all non-word chars
				.replace(/\-\-+/g, '-')     // Replace multiple - with single -
				.replace(/^-+/, '')         // Trim - from start of text
				.replace(/-+$/, '');        // Trim - from end of text
			// jscs:enable
		}
	});
})(window.TREASURY);
