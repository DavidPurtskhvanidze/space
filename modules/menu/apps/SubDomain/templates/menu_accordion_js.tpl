{require component="jquery" file="jquery.js"}
{require component="jquery-ui" file="jquery-ui.js"}
{require component="jquery-ui" file="css/smoothness/jquery-ui.css"}
<script type="text/javascript">
	jQuery(document).ready(function () {
		function makeAccordion(selector) {
			$(selector).on('click', '.tab > a', function () {
				$(this).parent('li').toggleClass('opened');
				$(this).parent('li').next().slideToggle('fast');
				return false;
			});
			$(selector + ' > .tab').not('.tab.opened').next().hide();
		}
		makeAccordion('.menu.accordion');
	});
</script>
