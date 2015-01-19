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
				thumbnails.removeClass('active');

				var thumbnail = $(this);
				thumbnail.addClass('active');

				var main = gallery.find('#mainImage, .mainImage');
				main.attr('src', thumbnail.attr('href'));
				main.parent().attr('href', thumbnail.data('image'));

				var image = $(this).find('img');
				if (image.length) {
					main.attr('title', image[0].title)
						.attr('alt', image[0].alt);
				}

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