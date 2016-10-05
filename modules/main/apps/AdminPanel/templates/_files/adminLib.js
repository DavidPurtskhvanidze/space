var AdminLib = {};

AdminLib.tooltip = function () {
	$('[data-rel="tooltip"]').tooltip(
			{
				html: true
			}
	);
}

AdminLib.init = function () {
	this.tooltip();
};

$(document).ready(function () {
	AdminLib.init();

	$("body").on('wfwappendedwith', function (event, element) {
		// The event "wfwcontentchanged" is triggered when the content of the element is changed
		element.on("wfwcontentchanged", function () {
			AdminLib.init();
		});
	});
});

(function ($) {
	$.fn.filterState = function (page) {
		var widgetBox = this;
		var cookieFilterState = $.cookie(page + '_filterState');

		widgetBox.addClass(cookieFilterState);

		$('.widget-header a', widgetBox).click(function () {
			if (widgetBox.hasClass('collapsed')) {
				$.cookie(page + '_filterState', '');
			} else {
				$.cookie(page + '_filterState', 'collapsed');
			}
		});



	}

}(jQuery));
