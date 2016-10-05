{if $hasError}<div class="error validation">{$error}</div>{/if}
<select class="searchTreeLevel1" name="{$id}[0]" id="{$id}[0]">
	<option value="">[[Miscellaneous!Any:raw]] [[FormFieldCaptions!{$levels_captions.0}:raw]]</option>
	{assign var='parent' value=0}
	{foreach from=$tree_values.$parent item=tree_value}
	<option value="{$tree_value.sid}"{if $value.0 == $tree_value.sid} selected="selected"{/if}>{tr mode="raw" domain="Property_$id"}{$tree_value.caption}{/tr}</option>
	{/foreach}
</select>
{include file="field_types^tree_js.tpl"}
