
<select name='{$id}[equal]' class="form-control">

	<option value="">[[Miscellaneous!Any]] [[{$caption}]]</option>

	{foreach from=$list_values item=list_value}

		<option value='{$list_value.id|escape}' {if $list_value.id == $value.equal}selected{/if} >[[{$list_value.caption}]]</option>

	{/foreach}

</select>
