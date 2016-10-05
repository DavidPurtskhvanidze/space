<select name="{$id}[1]"
        class="searchTreeLevel2 form-control {if $hasError}has-error{/if}"
        {if $hasError}data-error="{$error}"{/if}>
	<option value="">[[Miscellaneous!Any:raw]] [[FormFieldCaptions!{$levels_captions.1}:raw]]</option>
	{assign var='parent' value=$value.0}
	{foreach from=$tree_values.$parent item=tree_value}
	<option value="{$tree_value.sid|escape}"{if $value.1 == $tree_value.sid} selected="selected"{/if}>{tr mode="raw" domain="Property_$id"}{$tree_value.caption}{/tr}</option>
	{/foreach}
</select><br />
