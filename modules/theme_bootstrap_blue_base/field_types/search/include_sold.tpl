{if $value.not_equal == "2" || ($REQUEST.action == "restore" && empty($value.not_equal))}
	{assign var="includeSoldItems" value=true}
{else}
	{assign var="includeSoldItems" value=false}
{/if}
<div class="form-control-static">
    <div class="custom-form-control">
        <input type="checkbox" id="{$id}[not_equal]" name="{$id}[more]" {if $includeSoldItems}checked="checked"{/if} value="0" />
        <label class="checkbox" for="{$id}[not_equal]">&nbsp;</label>
    </div>
</div>
