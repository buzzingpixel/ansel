(function(F, $W) {
	'use strict';

	// Create storage for elements to make width aware
	var elements = {};

	// Create a timer for watching the window resize
	var windowResizeTimer = null;

	// Create a variable for default sizes
	var defaultSizes = [
		400,
		500,
		600,
		700,
		800,
		900,
		1000,
		1100,
		1200,
		1300,
		1400,
		1500,
		1600,
		1700,
		1800
	];

	/**
	 * Prep element for add/remove
	 *
	 * @param {object} el DOM or jQuery object
	 * @return {jQuery}
	 */
	var elPrep = function(el) {
		// Cast the element if necesary
		var $el = $(el);
		// Start a name variable
		var name;

		// If there is no name, set one
		if (! $el.data('widthAwareName')) {
			name = Math.floor((Math.random() * 99999999999) + 1);
			$el.data('widthAwareName', name);
		}

		// return the element
		return $el;
	};

	/**
	 * Update element by name
	 *
	 * @param {string} - name
	 */
	var updateElementByName = function(name) {
		// Get the element
		var element = elements[name];
		// Get the element width
		var width = element.$el.outerWidth();
		// Set up sizes array
		var sizes = [];

		// Loop through the class settings
		element.sizes.forEach(function(size) {
			// If width is equal or exceededs size threshold, add size to array
			if (width >= size) {
				if (sizes.indexOf(size) < 0) {
					sizes.push(size);
				}
			}
		});

		// Update the data-widths attribute
		element.$el.attr('data-widths', sizes.join(' '));
	};

	/**
	 * Update an element
	 *
	 * @param {jQuery} - $el
	 */
	var updateElement = function($el) {
		updateElementByName($el.data('widthAwareName'));
	};

	/**
	 * Update elements
	 */
	var updateElements = function() {
		// Loop through elements
		for (var i in elements) {
			// Send the element to the updateElement function
			updateElementByName(i);
		}
	};

	// Object for public API
	F.widthAwareness = {
		/**
		 * Add element to watch
		 *
		 * @param {string|object} el - Selector, DOM Object, or jQuery object
		 * @params {array|num} Sizes
		 */
		add: function(el) {
			// Get the prepped element
			var $el;
			var args = arguments;

			if (! el) {
				throw 'A DOM or jQuery element, or class selector must be provided';
			}

			// Prep the element
			$el = elPrep(el);

			// Make sure element has length
			if (! $el.length) {
				return;
			}

			// In case there is more than one element, loop through each
			$el.each(function() {
				// Get this element
				var $this = $(this);
				var name = $this.data('widthAwareName');

				// Create a storage object if it does not already exist
				if (! elements[name]) {
					elements[name] = {};
					elements[name].$el = $this;
					elements[name].sizes = [];
				}

				// Add sizes to elements array
				if (args.length > 1) {
					for (var i = 1; i < args.length; i++) {
						// Check if the argument is an array
						if (args[i].constructor === Array) {
							// Loop through the array items
							args[i].forEach(function(size) {
								// Push the items into the sizes array
								elements[name].sizes.push(parseInt(size));
							});
						// Otherwise add the argument directly to the sizes array
						} else {
							elements[name].sizes.push(parseInt(args[i]));
						}
					}
				} else {
					elements[name].sizes = defaultSizes;
				}

				// Set up triggering
				$this.on('widthAwarenessCheck', function() {
					updateElement($this);
				});

				// Make sure window is ready
				$(function() {
					// Run the first update on this element
					updateElement($this);
				});
			});
		},

		/**
		 * Remove element from watch
		 *
		 * @param {string|object} el - Selector, DOM Object, or jQuery object
		 */
		remove: function(el) {
			// Get the prepped element
			var $el = elPrep(el);

			// In case there is more than one element, loop through each
			$el.each(function() {
				// Get this element
				var $this = $(this);
				var name = $this.data('widthAwareName');

				// Check if the element is in storage
				if (elements[name]) {
					// Remove elements widthawareness trigger
					elements[name].$el.off('widthAwarenessCheck');

					// Remove all data-widths attribute
					elements[name].$el.attr('data-widths', null);

					// Delete the element from storage if it's there
					delete elements[name];
				}
			});
		},

		/**
		 * Watch a selector
		 *
		 * @param {string} sel - Selector
		 * @params {array|num} Sizes
		 */
		watchSelector: function(sel) {
			// Create a timer variable
			var timer;

			// Save a reference to self
			var self = this;

			// Start a var for arguments
			var sizes = [];

			if (! sel) {
				throw 'A selector must be provided';
			}

			// Loop through all arguments except the first one
			if (arguments.length > 1) {
				for (var i = 1; i < arguments.length; i++) {
					// Check if the argument is an array
					if (arguments[i].constructor === Array) {
						// Loop through the array items
						arguments[i].forEach(function(size) {
							// Push the items into the sizes array
							sizes.push(size);
						});
					// Otherwise add the argument directly to the sizes array
					} else {
						sizes.push(arguments[i]);
					}
				}
			} else {
				sizes = defaultSizes;
			}

			// Add the selector
			self.add(sel, sizes);

			// Watch for new elements with this selector being added
			document.addEventListener('DOMSubtreeModified', function() {
				// Clear any previous timeout
				clearTimeout(timer);

				// Set a new timeout
				timer = setTimeout(function() {
					// Add the selector
					self.add(sel, sizes);
				}, 250);
			}, false);
		}
	};

	// Add base width awareness to elements with class or data element
	F.widthAwareness.watchSelector('[data-width-aware="true"]', defaultSizes);
	F.widthAwareness.watchSelector('.js-width-aware', defaultSizes);

	// Run checks on window resize
	$W.on('resize', function() {
		// Clear the timeout
		clearTimeout(windowResizeTimer);

		// Only run check if the window resize is a multiple of 10
		// otherwise set a timer to check when window is done resizing
		// this throttling should help with performance
		if ($W.width() % 10 === 0) {
			updateElements();
		} else {
			windowResizeTimer = setTimeout(function() {
				updateElements();
			}, 50);
		}
	});
})(window.TREASURY, $(window));
