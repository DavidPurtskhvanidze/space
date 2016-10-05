$(function () {
	$(".more-less").each(function () {
		var $those = $(this);
		$('.show-more', $(this)).click(function () {
			$those.addClass('open', 300);
			return false;
		});
		$('.show-less', $(this)).click(function () {
			$those.removeClass('open', 300);
			return false;
		});
	});
});
