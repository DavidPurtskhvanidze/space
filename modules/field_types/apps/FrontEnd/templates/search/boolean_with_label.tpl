{if $parameters.label}
	{$label = $parameters.label}
{else}
	{$label = $caption}
{/if}
<input type="hidden" name="{$id}[equal]" value="" />
<label>
	<input type="checkbox" name="{$id}[equal]" {if $value.equal}checked="checked"{/if} value="1" />
	[[$label]]
</label>
