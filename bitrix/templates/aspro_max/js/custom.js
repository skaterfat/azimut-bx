/*
You can use this file with your scripts.
It will not be overwritten when you upgrade solution.
*/

//window.onerror = function (msg, url, line, col, exception) { BX.ajax.get('/ajax/error_log_logic.php', { data: { msg: msg, exception: exception, url: url, line: line, col: col } }); }

jQuery(document).ready(function($) {

	$('.section-tags-more a').on('click', function(event) {
		event.preventDefault();

		var showText = $(this).data('show-text'),
			hideText = $(this).data('hide-text');

		if($(this).hasClass('active')) {

			$(this).parent().siblings('.section-tags').css({height: '40px'});
			$(this).removeClass('active').html(showText);


		}
		else {

			$(this).parent().siblings('.section-tags').css({height: 'auto'});
			$(this).addClass('active').html(hideText);

		}

	});

	$('.btn--fast-view-click').on('click', function(event) {
		event.preventDefault();
		$(this).closest('.table-view__item').find('.fast_view_block').trigger('click');
	});

});