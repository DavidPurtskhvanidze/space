<input type="hidden" name="{$id}[multilist]" value="" />
<ul>
	{foreach from=$list_values item=list_value}
		<li>
			<input type="checkbox" id="{$id}[{$list_value.rank}]" name="{$id}[multilist][{$list_value.rank}]" class="form-control" value="1" {if isset($value['multilist'][$list_value.rank])}checked{/if}> <label for="{$id}[{$list_value.rank}]">{tr domain="Property_$id"}{$list_value.caption}{/tr}</label>
		</li>
	{/foreach}
</ul>
