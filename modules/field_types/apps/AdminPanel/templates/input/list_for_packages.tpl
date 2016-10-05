<select name='{$id}' class="form-control {if $hasError}has-error tooltip-error{/if}" {if $hasError}data-rel="tooltip" data-placement="top" title="{$error}"{/if}>
	<option value="">[[Miscellaneous!Select:raw]] [[FormFieldCaptions!{$caption}:raw]]</option>
	{assign var="current_group_name" value=""}
	{foreach from=$list_values item=list_value}
		{if $current_group_name!=$list_value.parent_name}
			{if $current_group_name!=""}</optgroup>{/if}
			<optgroup label="[[{$list_value.parent_name}]]">
		{/if}
		<option value='{$list_value.sid|escape}' {if $list_value.sid == $value}selected{/if} >[[{$list_value.caption}]]</option>
		{assign var="current_group_name" value=$list_value.parent_name}
	{/foreach}
	{if $list_values|@count > 0}</optgroup>{/if}
</select>
