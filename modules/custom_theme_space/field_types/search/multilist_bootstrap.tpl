<input type="hidden" name="{$id}[multilist]" value="" />
{foreach from=$list_values item=list_value}
	<div class="checkbox option {$list_value.caption}">
		<div class="checkbox">
			<label>
				<input type="checkbox" name="{$id}[multilist][{$list_value.rank}]" value="1" {if isset($value['multilist'][$list_value.rank])}checked{/if} /> {tr domain="Property_$id"}{$list_value.caption}{/tr}
			</label>
		</div>
	</div>
{/foreach}
