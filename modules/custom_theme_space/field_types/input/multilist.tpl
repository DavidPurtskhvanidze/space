<input type="hidden" name="{$id}" value="0">
<div class="multilist-box">
	<div class="row">
		{foreach from=$list_values item=list_value}
			<div class="col-md-6">
				<div class="checkbox">
					<label>
						<input type="checkbox" id="{$id}[{$list_value.rank}]" name="{$id}[{$list_value.rank}]" value="1" {if isset($value[$list_value.rank])}checked{/if} /> {tr domain="Property_$id"}{$list_value.caption}{/tr}
					</label>
				</div>
			</div>
		{/foreach}
	</div>
</div>

