/**
 * Currently need this JS snippet to make deleting from the sidebar work
 *
 * https://ellislab.com/forums/viewthread/249040/
 */

$(document).ready(function() {
	$('.sidebar .folder-list .remove a.m-link').click(function(e) {
		var $this = $(this);
		var modalIs = '.' + $(this).attr('rel');

		$(modalIs + ' .checklist').html(''); // Reset it
		$(modalIs + ' .checklist')
			.append('<li>' + $this.data('confirm') + '</li>');
		$(modalIs + ' input[name="id"]').val($this.data('id'));

		e.preventDefault();
	});
});
