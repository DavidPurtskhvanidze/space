{if $value.not_equal == "2" || ($REQUEST.action == "restore" && empty($value.not_equal))}
	{assign var="includeSoldItems" value=true}
{else}
	{assign var="includeSoldItems" value=false}
{/if}

<input type="hidden" name="{$id}[not_equal]" value="1" />
<div class="form-control-static">
	<input type="checkbox" name="{$id}[not_equal]" {if $includeSoldItems}checked="checked"{/if} value="2" />
</div>

{*
	not_equal 1 -> 0 or null
	not_equal 2 -> 1, 0 or null
*}
