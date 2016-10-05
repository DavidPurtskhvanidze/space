function makeTooltips() {
	$("*[data-error]").tooltip({
		items: "[data-error]",
		position: { my: "left top", at: "right+5 top", collision: "flipfit" },
		content: function () {
			return $(this).data("error");
		}
	});
}
$(function () {
	makeTooltips();

	// Use tooltip plugin for the dynamically created elements. In our case it is dialog window with content loaded via ajax.
	// Both of the events "wfwappendedwith" and "wfwcontentchanged" are custom, and they are triggered manually.
	// The event "wfwappendedwith" is triggered when the element appended with the new element
	$("body").on('wfwappendedwith', function (event, element) {
		// The event "wfwcontentchanged" is triggered when the content of the element is changed
		element.on("wfwcontentchanged", function () {
			makeTooltips();
		});
	});
});
