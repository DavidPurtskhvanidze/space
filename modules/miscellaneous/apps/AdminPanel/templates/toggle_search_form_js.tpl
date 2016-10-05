{require component="jquery" file="jquery.js"}
{require component="jquery-ui" file="css/smoothness/jquery-ui.css"}
{require component="jquery-cookie" file="jquery.cookie.js"}
<script type="text/javascript">
	var openSearchFormText = "[[Click to open the search form:raw]]";
	var hideSearchFormText = "[[Click to hide the search form:raw]]";
	var requestAction = "{$REQUEST.action}";
	{literal}
	$(document).ready(function(){
		var formId = $(".searchForm h2").next('form').attr("id");
		var hideFormCookieVarName = "hide" + formId;
		$(".searchForm h2").click(function(){
			$(this).next('form').toggle("blind");
			$(this).toggleClass("active");
			if ($(this).hasClass("active"))
			{
				$.cookie(hideFormCookieVarName, "0");
				$(this).attr("title", hideSearchFormText);
			}
			else
			{
				$.cookie(hideFormCookieVarName, "1");
				$(this).attr("title", openSearchFormText);
			}
		});
		if (requestAction == 'restore' && $.cookie(hideFormCookieVarName) == 1)
		{
			$(".searchForm h2").click();
		}
	});
	{/literal}
</script>
