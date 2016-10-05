{require component="jquery" file="jquery.js"}
<script type="text/javascript">
	{literal}
	$(document).ready(function(){
		$(".searchResultItem").mouseover(function(){
			$(".actionSelector > a", $(this)).addClass("active");
			$(".actionSelector > ul", $(this)).show();
		});
		$(".searchResultItem").mouseout(function(){
			$(".actionSelector > a", $(this)).removeClass("active");
			$(".actionSelector > ul", $(this)).hide();
		});
		$(".searchResultItem .actionSelector > ul").hide();
		$(".searchResultItem .actionSelector > a").click(function(){
			return false;
		}).removeClass("active");
	});
	{/literal}
</script>
