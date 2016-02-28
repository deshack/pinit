(function($) {
	var types = new Array( 'pin', 'profile', 'board' );

	function onLoad() {
		$('.widget[id*="pit_pinterest"]').each(function() {
			var widget = $(this);
			var type = widget.find('input[type=radio]:checked').val();
			showHideFields(widget, type);
		});
	}

	function showHideFields(widget, type) {
		$.each( types, function() {
			if ( this == type )
				return;
			widget.find('.' + this + '-control').hide();
			widget.find('.' + this + '-help').hide();
		});
		widget.find('.' + type + '-control').show();
		widget.find('.' + type + '-help').show();
	}

	function addListener() {
		$('.widget[id*="pit_pinterest"]').on( 'change', 'input[type=radio]', function() {
			var widget = $(this).parents('.widget');
			var type = $(this).val();
			showHideFields(widget, type);
		});
	}

	$(document).ready( onLoad );
	$(document).ajaxComplete( onLoad );

	$(document).ready( addListener );
	// The following attaches the event listener to newly added widgets, but also
	// to already existing widgets. This may cause performance issues.
	$(document).ajaxComplete( addListener );
})(jQuery);
