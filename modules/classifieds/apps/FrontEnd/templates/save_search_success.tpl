<p class="success">
	<span>[[Your search has been saved.]]</span>
</p>
{require component="jquery" file="jquery.js"}

<script type="text/javascript">
	$(".savedSearchesLink").fadeIn("fast");
	$(".savedSearchesCount").text({$savedSearchesCount});//View Saved Searches counter update in Manage Search part
</script>
