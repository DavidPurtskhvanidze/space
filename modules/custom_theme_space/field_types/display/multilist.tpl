<ul class="list-unstyled multi-list-box">
    {foreach from=$list_values item=list_value}
        {if isset($value[$list_value.rank])}
            <li>
                <span class="glyphicon glyphicon-ok"></span>
                <span>{tr domain="Property_$id"}{$list_value.caption}{/tr}</span>
            </li>
        {/if}
    {/foreach}
</ul>
<div class="clearfix"></div>
