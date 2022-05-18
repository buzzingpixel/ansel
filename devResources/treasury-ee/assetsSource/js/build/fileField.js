(function(F) {
	'use strict';

	F.fn.make('fileField', {
		/**
		 * Container
		 */
		$el: null,

		/**
		 * Add button
		 */
		$btn: null,

		/**
		 * Thumb
		 */
		$thumb: null,

		/**
		 * Thumb wrapper
		 */
		$thumbWrapper: null,

		/**
		 * Initialize
		 *
		 * @param {jQuery} $btn
		 */
		init: function($el) {
			// Save a reference to the function set
			var self = this;

			// Set the element
			self.$el = $el;

			// Set the button
			self.$btn = $el.find('.js-treasury-file-field-add');

			// Set the thumb wrapper
			self.$thumbWrapper = $el.find('.js-thumb-wrapper');

			// Set the thumb
			self.$thumb = $el.find('.js-image-thumb');

			// Set the input
			self.$input = $el.find('.js-treasury-file-input');

			// Set the file callback
			self.$btn.TreasuryFilePicker({
				callback: function(file) {
					self.fileSelected(file);
				}
			});

			// Set remove file click
			$el.find('.js-thumb-delete').on('click', function() {
				self.fileRemoved();
			});
		},

		fileSelected: function(file) {
			// Save a reference to the function set
			var self = this;

			// Create a new image
			var img = new Image();

			// jscs:disable
			img.src = file.thumb_url; // jshint ignore:line

			// Set the file id to the image input
			self.$input.val(file.id).trigger('change'); // jshint ignore:line
			// jscs:enable

			// Show the image thumbnail
			self.$thumb.html(img);
			self.$thumbWrapper.jsShow();

			// Hide the add file button
			self.$btn.jsHide();
		},

		fileRemoved: function() {
			// Save a reference to the function set
			var self = this;

			self.$input.val('').trigger('change');
			self.$thumbWrapper.jsHide();
			self.$btn.jsShow();
		}
	});
})(window.TREASURY);
