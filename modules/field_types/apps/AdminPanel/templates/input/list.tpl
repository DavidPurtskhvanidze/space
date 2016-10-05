
<select name='{$id}' class="form-control {if $hasError}has-error tooltip-error{/if}" {if $hasError}data-rel="tooltip" data-placement="top" title="{$error}"{/if}>

	<option value="">[[Miscellaneous!Select:raw]] [[FormFieldCaptions!{$caption}:raw]]</option>

	{foreach from=$list_values item=list_value}

		<option value='{$list_value.id|escape}' {if $list_value.id == $value}selected{/if} >{tr mode="raw" domain="Property_$id"}{$list_value.caption}{/tr}</option>

	{/foreach}

</select>
