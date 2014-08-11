(function($) {
	var types = new Array( 'pin', 'profile', 'board' );

	function onLoad() {
		var checked = $('input[name="widget-pit_pinterest[2][select]"]:radio:checked').val();
		$.each( types, function() {
			if ( this == checked )
				return;
			$('.' + this + '-control').hide();
			$('.' + this + '-help').hide();
		});
		$('.' + checked + '-control').show();
		$('.' + checked + '-help').show();
	}

	$(document).ready( onLoad );
	$(document).ajaxComplete( onLoad );

	$('#widgets-right').on( 'change', 'input[name="widget-pit_pinterest[2][select]"]:radio', function() {
		var checked = $(this).val();
		$.each( types, function() {
			if ( this == checked ) return;
			$('.' + this + '-control').hide();
			$('.' + this + '-help').hide();
		});
		$('.' + checked + '-control').show();
		$('.' + checked + '-help').show();
	});
})(jQuery);