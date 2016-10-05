<input type="hidden" name="{$id}" value="0">
<div class="row">
	{foreach from=$list_values item=list_value}
		<div class="col-xs-12 col-sm-4 col-md-3">
			<div class="custom-form-control">
                <input type="checkbox" id="{$id}[{$list_value.rank}]" name="{$id}[{$list_value.rank}]" value="1" {if isset($value[$list_value.rank])}checked{/if} />
                <label class="checkbox" for="{$id}[{$list_value.rank}]">{tr domain="Property_$id"}{$list_value.caption}{/tr}</label>
			</div>
		</div>
	{/foreach}
</div>
