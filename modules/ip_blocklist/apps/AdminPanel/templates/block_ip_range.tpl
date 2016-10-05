{if $actionDone}
	{if $returnBackUri}
		<script type="text/javascript">
			this.location.href = "{$returnBackUri}&ip={$added_ip_range}";
		</script>
	{else}
		<script type="text/javascript">
			this.location.href = this.location.href + "&action=blocklist_add_ip&target={$added_ip_range}";
		</script>
	{/if}
{else}
	{include file="add_ip_range_form.tpl"}
{/if}
