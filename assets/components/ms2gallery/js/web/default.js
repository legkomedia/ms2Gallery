var ms2Gallery = {
	initialize: function(selector) {
		var galleries = $(selector);
		if (!galleries.length) {
			return false;
		}

		galleries.each(function() {
			var gallery = $(this);
			var thumbnails = gallery.find('.thumbnail');
			thumbnails.on('click', function(e) {
				e.preventDefault();
				var thumbnail = $(this);
				thumbnails.removeClass('active');
				thumbnail.addClass('active');
				var main = gallery.find('#mainImage, .mainImage');
				main.attr('src', thumbnail.attr('href'))
					.parent().attr('href', thumbnail.data('image'));
				return false;
			});
			gallery.find('.thumbnail:first').click();
		});

		return true;
	}
};

$(document).ready(function() {
	ms2Gallery.initialize('#msGallery, .ms2Gallery');
});