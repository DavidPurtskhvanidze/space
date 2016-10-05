function openContentInDialog(windowTitle, content, width, isModal, callBack)
{
    $.loader.open();
    var $dialog = getDialogTemplate(windowTitle, width);

    $dialog.appendTo("body");
    $("body").trigger("wfwappendedwith", [$dialog]);

    $dialog
        .on('click', '.dialog-close', function () {
            $dialog.modal('hide');
            return false;
        })
        .on('hidden.bs.modal', function () {
            $dialog.remove();
        });

    $('.modal-body', $dialog).html(content);

    if (typeof callBack != "undefined") {
        try {
            window[callBack]($dialog);
        }
        catch (e) {
        }
    }
    $dialog.modal();
    $.loader.close(true);
    return false;
}

function openDialogWindow(windowTitle, url, width, isModal, callBack) {

    $.loader.open();

	var $dialog = getDialogTemplate(windowTitle, width);

	$dialog.appendTo("body");
	$("body").trigger("wfwappendedwith", [$dialog]);

	$dialog
			.on('click', '.dialog-close', function () {
				$dialog.modal('hide');
				return false;
			})
			.on('hidden.bs.modal', function () {
				$dialog.remove();
			});

	$('.modal-body', $dialog).load(url, getCallbackForBindInteractions(url, function () {
		$dialog.modal();
        $.loader.close(true);
	}));

	if (typeof callBack != "undefined") {
		try {
			window[callBack]($dialog);
		}
		catch (e) {
		}
	}

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
		dialogWindow.load(formUrl, $(this).serializeArray(), getCallbackForBindInteractions(formUrl));
		return false;
	});

	$('a:not(.respondInMainWindow)', dialogWindow).bind('click', function () {
		var linkHref = $(this).attr('href');
		if (linkHref != '#') {
			dialogWindow.load(linkHref, getCallbackForBindInteractions(linkHref));
			return false;
		}
	});

}

function getCallbackForBindInteractions(url, extraCallbackOnLoad) {

	return function (response, status, xhr) {

		bindInteractionsInDialog($(this), url);
		$(this).trigger("wfwcontentchanged");

		if (status == "error") {
			$(this).html('<div class="alert alert-danger">' + xhr.responseText + '</div>');
		}

		if (typeof extraCallbackOnLoad != "undefined")
			extraCallbackOnLoad();
	}
}

function getDialogTemplate(windowTitle, width) {
	var template =
			'<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">' +
			'<div class="modal-dialog">' +
			'<div class="modal-content">' +
			'<div class="modal-header">' +
			'<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>' +
			'<h4 class="modal-title">' + windowTitle + '</h4>' +
			'</div>' +
			'<div class="modal-body">' +
			'</div>' +
			'<div class="modal-footer">' +
			'</div>' +
			'</div>' +
			'</div>' +
			'</div>';

	var $dialog = $(template);

	if (width > 600) {
		$('.modal-dialog', $dialog).addClass('modal-lg');
	}
	return $dialog;
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
