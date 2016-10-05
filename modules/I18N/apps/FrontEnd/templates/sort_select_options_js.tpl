{require component="jquery" file="jquery.js"}
<script type="text/javascript">
$(document).ready(function(){
	sortOptions('select');
	$('body').on('onChildChanged', "select[name*='\[tree\]']", function(){
		sortOptions(this);
	});
});

function sortOptions(selector)
{
	$(selector).each(function(){
		$(this).append($(this).find('option:not(:first)').remove().sort(function(a, b) {
			var at = $(a).text(), bt = $(b).text();
			return (at > bt)?1:((at < bt)?-1:0);
		}));
	});
}
</script>
