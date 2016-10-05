{require component="jquery" file="jquery.js"}
{require component="jquery-ui" file="jquery-ui.js"}
{require component="jquery-cookie" file="jquery.cookie.js"}
<script type="text/javascript">

	var openSearchFormText = "[[Click to open the search form:raw]]";
	var hideSearchFormText = "[[Click to hide the search form:raw]]";
	var requestAction = "{$REQUEST.action}";

	$(document).ready(function () {
		var formId = $(".searchForm h1").next('form').attr("id");
		var hideFormCookieVarName = "hide" + formId;
		$(".searchForm h1").click(function () {
			toggleSearchForm($(this), hideFormCookieVarName, true);
		});

		if (requestAction == 'restore' && $.cookie(hideFormCookieVarName) == 1) {
			toggleSearchForm($(".searchForm h1"), hideFormCookieVarName, false);
		}
	});

	function toggleSearchForm($formHeader, hideFormCookieVarName, toggleWithBlind) {
		if (toggleWithBlind) {
			$formHeader.next('form').toggle("blind");
		}
		else {
			$formHeader.next('form').toggle();
		}

		$formHeader.toggleClass("active");
		if ($formHeader.hasClass("active")) {
			$.cookie(hideFormCookieVarName, "0");
			$formHeader.attr("title", hideSearchFormText);
		}
		else {
			$.cookie(hideFormCookieVarName, "1");
			$formHeader.attr("title", openSearchFormText);
		}
	}
</script>
