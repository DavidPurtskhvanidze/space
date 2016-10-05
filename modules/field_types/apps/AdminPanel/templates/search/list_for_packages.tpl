<select name='{$id}[equal]' class="form-control">
    <option value="">[[Miscellaneous!Any]] [[{$caption}]]</option>
	<option value="deleted" {if $value.equal == 'deleted' || is_array($value.not_in) }selected{/if}>[[Deleted]] [[$caption]]</option>
    {assign var="current_group_name" value=""}
	{foreach from=$list_values item=list_value}
        {if $current_group_name!=$list_value.parent_name}
            {if $current_group_name!=""}</optgroup>{/if}
            <optgroup label="[[{$list_value.parent_name}]]">
        {/if}
		<option value='{$list_value.sid|escape}' {if $list_value.sid == $value.equal}selected{/if} >[[{$list_value.caption}]]</option>
    {assign var="current_group_name" value=$list_value.parent_name}
	{/foreach}
    {if $list_values|@count > 0}</optgroup>{/if}

</select>
