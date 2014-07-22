var ms2Gallery = {
	initialize: function(selector) {
		var gallery = $(selector);
		if (!gallery.length) {return false;}

		$(document).on('click', selector + ' .thumbnail', function(e) {
			var src = $(this).attr('href');
			var href = $(this).data('image');
			$('#mainImage', gallery).attr('src', src).parent().attr('href', href);
			return false;
		});

		$('.thumbnail:first', gallery).trigger('click');
        return true;
	}
};

$(document).ready(function() {
	ms2Gallery.initialize('#msGallery');
});