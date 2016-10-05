<input type="hidden" name="{$id}" value="0">
<div class="control-group">
	{foreach from=$list_values item=list_value}
		<div class="checkbox no-padding-left">
			<label>
				<input type="checkbox" id="{$id}[{$list_value.rank}]" name="{$id}[{$list_value.rank}]" class="ace" value="1" {if isset($value[$list_value.rank])}checked{/if}>
				<span class="lbl"> {tr domain="Property_$id"}{$list_value.caption}{/tr}</span>
			</label>
		</div>
	{/foreach}
</div>
