
{require component="jquery" file="jquery.js"}
{require component="jquery-ui" file="jquery-ui.js"}
<script type="text/javascript">
	{literal}
	$(document).ready(function(){
		var divToExpand = $(".mainContent"); // element to expand if submenu does not fit the window and it is cutted. See #2975
		$(".multilevelMenu li ul").hide(); // hide all sub-menus
		$(".multilevelMenu li ul").prev("a").click(function(){
			if ($(this).hasClass("disabled"))
				return false;

			var subMenu = $(this).next("ul");
			if (subMenu.is(":visible")) // hide sub-menu
			{
				// hide all sub-menus of the current sub-menu
				$("ul", subMenu).hide();
				subMenu.slideToggle(false);
				$(this).parent().toggleClass("activeMenu");
			}
			else // show sub-menu
			{
				// hide all the sibling sub-menus and theirs sub-menus
				$("ul", subMenu.parent().parent()).hide();
				$("ul", subMenu.parent().parent()).parent().removeClass("activeMenu");
				subMenu.slideToggle(true, function(){
					var missedDivHeight = Math.ceil(subMenu.outerHeight(true) + subMenu.offset().top - divToExpand.offset().top) - divToExpand.height();
					if (missedDivHeight > 0)
						$("<div></div>")
								.height(missedDivHeight)
								.css("clear", "both")
								.appendTo(divToExpand);
				});
				$(this).parent().toggleClass("activeMenu");
			}
			return false;
		}).addClass("caption");
	});
	{/literal}
</script>
