(function(F) {
	'use strict';

	// Prepare var for file picker modal
	var $treasuryFilePickerModal;

	// Timer
	var timer;

	// Set up a function to instantiate buttons
	var instantiateButtons = function() {
		// Loop through filepicker trigers
		$('.js-treasury-filepicker').each(function() {
			var $el = $(this);

			// Check if this has already been init
			if (! $el.data('treasuryPickerInit')) {
				// Add any $('.js-treasury-filepicker')s on the DOM
				F.filePicker.addTrigger(this);
			}

			// Add data attribute that this filepicker has been instantiated
			$el.data('treasuryPickerInit', true);
		});
	};

	$.fn.TreasuryFilePicker = function(obj) {
		obj = obj || {};
		this.each(function() {
			this.TreasuryFilePickerCallback = obj.callback;
		});
	};

	F.fn.make('filePicker', {
		/**
		 * Button
		 */
		$btn: null,

		/**
		 * Modal link
		 */
		href: null,

		/**
		 * Add trigger
		 * @param {selector|DOMObject|jQuery} el [description]
		 */
		addTrigger: function(el) {
			F.fn.clone('filePicker', true, $(el));
		},

		/**
		 * Initialize location type switching
		 *
		 * @param {jQuery} $btn
		 */
		init: function($btn) {
			// Save a reference to the function set
			var self = this;

			// Save the button
			self.$btn = $btn;

			// Set the href
			self.href = self.$btn.attr('href');

			self.$btn.on('click.treasury', function() {
				self.getHtml();
			});

			setTimeout(function() {
				// Watch for click on button
				self.$btn.off('click.treasury');
				self.$btn.on('click', function() {
					self.getHtml();
				});
			}, 100);

			setTimeout(function() {
				// Watch for click on button
				self.$btn.off('click.treasury');
				self.$btn.on('click', function() {
					self.getHtml();
				});
			}, 500);
		},

		/**
		 * Get modal HTML
		 *
		 * @param {string} - href
		 */
		getHtml: function(href) {
			// Save a reference to the function set
			var self = this;

			// Check if href is incoming
			href = href || this.href;

			// Run the ajax call
			$.ajax({
				url: href,
				success: function(html) {
					self.render(html);
				}
			});
		},

		/**
		 * Render the HTML into the modal
		 *
		 * @param {string} - html
		 */
		render: function(html) {
			// Save a reference to the function set
			var self = this;

			// Cast the HTML
			var $html = $(html);

			// Hijack hrefs
			$html.on('click', '[href]', function(e) {
				// Get the anchor
				var $anchor = $(this);

				// Prevent default
				e.preventDefault();

				if ($anchor.hasClass('has-sub')) {
					$anchor.siblings('.sub-menu').toggle();
				} else {
					// Get new HTML
					self.getHtml($anchor.attr('href'));
				}
			});

			/**
			 * Hijack search submission
			 */
			$html.on('submit', '.js-file-search', function(e) {
				// Save a reference to this form element
				var $el = $(this);

				// Set up the form data for submission
				var formData = new FormData($el.get(0));

				// Get the submit button
				var $btn = $el.find('.btn');

				// Prevent default
				e.preventDefault();

				// Set submit button to working
				$btn.addClass('work');

				// Run the ajax request for form submission
				$.ajax({
					url: $el.attr('action'),
					type: 'POST',
					data: formData,
					cache: false,
					contentType: false,
					processData: false,
					beforeSend: function(xhr) {
						// EE gets weird when the X-Request-With header is set
						xhr.setRequestHeader('X-Requested-With', {
							toString: function() {
								return '';
							}
						});
					},
					success: function(html) {
						self.render(html);
					}
				});
			});

			/**
			 * Hijack upload submission
			 */
			$html.on('submit', '.js-upload-file', function(e) {
				// Save a reference to this form element
				var $el = $(this);

				// Set up the form data for submission
				var formData = new FormData($el.get(0));

				// Get the submit button
				var $btn = $el.find('.btn');

				// Prevent default
				e.preventDefault();

				// Set submit button to working
				$btn.val($btn.data('workText')).addClass('work');

				// Run the ajax request for form submission
				$.ajax({
					url: $el.attr('action'),
					type: 'POST',
					data: formData,
					cache: false,
					contentType: false,
					processData: false,
					beforeSend: function(xhr) {
						// EE gets weird when the X-Request-With header is set
						xhr.setRequestHeader('X-Requested-With', {
							toString: function() {
								return '';
							}
						});
					},
					success: function(obj) {
						// If there are errors, let’s load the specified URL
						// as HTL
						if (obj.hasErrors === true) {
							self.getHtml(obj.loadUrl);

						// If there are no errors, run callback and close modal
						} else {
							self.runCallback(obj.loadUrl);

							$treasuryFilePickerModal.find('.m-close').click();
						}
					}
				});
			});

			// Watch for clicks on the file row
			$html.on('click', '.js-file-row', function() {
				// Save a refrence to the element
				var $el = $(this);

				// Something in EE is triggering this click twice, check if it
				// has already been hit
				if ($el.data('wasSelected')) {
					return;
				}

				// Set was selected
				$el.data('wasSelected', true);

				self.runCallback($el.data('fileJsonHref'));

				$treasuryFilePickerModal.find('.m-close').click();
			});

			// Add the HTML to the DOM
			$treasuryFilePickerModal.find('.box').replaceWith($html);
		},

		/**
		 * Run callback
		 *
		 * @param string href
		 */
		runCallback: function(href) {
			// Save a reference to the function set
			var self = this;

			// Get the callback
			var callback = self.$btn.get(0).TreasuryFilePickerCallback;

			// If there is no callback, there's not really anyting to do here
			if (! callback) {
				return;
			}

			// Run the ajax call
			$.ajax({
				url: href,
				dataType: 'json',
				success: function(obj) {
					callback(obj);
				}
			});
		}
	});

	// On document ready
	$(function() {
		// Extend jQuery so we can watch for hide/show events
		$.each(['show', 'hide'], function(i, ev) {
			var el = $.fn[ev];
			$.fn[ev] = function() {
				this.trigger(ev);
				return el.apply(this, arguments);
			};
		});

		// Set the file picker modal variable
		$treasuryFilePickerModal = $('.js-treasury-file-picker-modal');

		// Instantiate buttons
		instantiateButtons();

		// Watch for new elements being added
		document.addEventListener('DOMSubtreeModified', function() {
			// Clear any previous timeout
			clearTimeout(timer);

			// Set a new timeout
			timer = setTimeout(function() {
				// Add the selector
				instantiateButtons();
			}, 250);
		}, false);

		// Watch for hiding of Treasury file picker modal
		$treasuryFilePickerModal.on('hide', function() {
			// Clear out contents
			$treasuryFilePickerModal.find('.box').html('');
		});
	});
})(window.TREASURY);
