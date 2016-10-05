{section name=foo start=0 loop=$tree_depth}
{assign var="index" value=$smarty.section.foo.index}
<select class="searchTreeLevel1" name="{$id}[{$index}]">
	<option value="">Any {$levels_captions.$index}</option>
	{*{defining parent of the curent selectbox}*}
	{if $index == 0}
		{assign var='parent' value=0}
	{else}
		{assign var='parentIndex' value=$index-1}
		{assign var='parent' value=$value.$parentIndex}
	{/if}
	{*{generating tree items based on parent}*}
	{foreach from=$tree_values.$parent item=tree_value}
	<option value="{$tree_value.sid|escape}"{if $value.$index == $tree_value.sid} selected="selected"{/if}>{$tree_value.caption}</option>
	{/foreach}
</select>
{/section}
{include file="field_types^input/tree_js.tpl"}
