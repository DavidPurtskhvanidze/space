function openDialogWindow(windowTitle, url, width, isModal, callBack) {
	var $dialog = $('<div></div>');
    $('<div class="loadingWithOverlay"></div>').appendTo('body');
    $dialog.appendTo("body");
	$("body").trigger("wfwappendedwith", [$dialog]);
	$dialog.dialog({
		dialogClass: 'dialogWindow',
		title: windowTitle,
		width: width,
		maxHeight: 650,
		position: ['top', 'top'],
		modal: isModal,
        show: 'hide',
        close: function (ev, ui) {
			$(this).remove();
		}
	});

	$dialog.load(url, function (response, status, xhr) {
		bindInteractionsInDialog($dialog, url);

		if (status == "error") {
			$(this).html('<div class="error">' + xhr.responseText + '</div>');
		}

		try {
			eval(callBack + '($dialog)');
		}
		catch(e) {
		}
        $(this).parent().delay(2000).show();
        $('.loadingWithOverlay').remove();
        $(this).dialog("option", "position", ['center', 'center']);
		$(this).trigger("wfwcontentchanged");
	});
	return false;
}
function bindInteractionsInDialog(dialogWindow, url) {
	$('form:not(.respondInMainWindow)', dialogWindow).bind('submit', function () {
		// Before getting form data we need to update CKeditor instances linked fields.
		// It fills up linked fields with values of the CKeditor instances.
		if (typeof CKEDITOR != 'undefined')
			for (var instance in CKEDITOR.instances)
				CKEDITOR.instances[instance].updateElement();

		var formUrl = $(this).attr('action');
		formUrl = (formUrl == '') ? url : formUrl;
		dialogWindow.load(formUrl, $(this).serializeArray(), function () {
			bindInteractionsInDialog(dialogWindow, formUrl);
			$(this).dialog("option", "position", ['center', 'center']);
			$(this).trigger("wfwcontentchanged");
		});
		return false;
	});
	$('a:not(.respondInMainWindow)', dialogWindow).bind('click', function () {
		var linkHref = $(this).attr('href');
		if (linkHref != '#') {
			dialogWindow.load(linkHref, function(response, status, xhr) {
				bindInteractionsInDialog(dialogWindow, linkHref);
				$(this).dialog("option", "position", ['center', 'center']);
				$(this).trigger("wfwcontentchanged");
			});
			return false;
		}
	});

	$('.dialog-close', dialogWindow).click(function () {
		dialogWindow.dialog("destroy");
	});
}
function openLinkInWindow(obj, target, params) {
	if (params) {
		window.open(obj.href, target, params);
	}
	else {
		window.open(obj.href, target);
	}
	return false;
}
