<div class="row">
	<input type="hidden" name="{$id}[multilist]" value="" />
	{foreach from=$list_values item=list_value}
		<div class="col-xs-6 col-sm-4 col-md-3">
			<div class="checkbox">
				<label>
					<input id="{$id}{$list_value.rank}" type="checkbox" name="{$id}[multilist][{$list_value.rank}]" value="1" {if isset($value['multilist'][$list_value.rank])}checked{/if} />
					{tr domain="Property_$id"}{$list_value.caption}{/tr}
				</label>
			</div>
		</div>
	{/foreach}
</div>
