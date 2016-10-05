<div class="row multi-list">
    {assign var='i' value=0}
	{foreach from=$list_values item=list_value}
		{if isset($value[$list_value.rank])}
            {$i  = $i + 1}
            <div class="col-sm-6 col-md-4 item">
				<i class="fa fa-check"></i>&nbsp;&nbsp;{tr domain="Property_$id"}{$list_value.caption}{/tr}
			</div>
            {if $i is div by 2}<div class="clearfix visible-sm"></div>{/if}
            {if $i is div by 3}<div class="clearfix visible-md visible-lg"></div>{/if}
		{/if}
	{/foreach}
</div>
