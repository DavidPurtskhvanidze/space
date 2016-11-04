{section name=foo start=0 loop=$tree_depth}
    {assign var="index" value=$smarty.section.foo.index}
    {if $parameters.id_prefix}
        {$id_attribute = " id=\"{$parameters.id_prefix}_{$id}{$index + 1}\""}
    {else}
        {$id_attribute = " id=\"input_{$id}{$index + 1}\""}
    {/if}
    <select name="{$id}[{$index}]"{$id_attribute}
            class="tree-select searchTreeLevel{$index + 1} form-control {if $hasError}has-error{/if}"
            {if $hasError}data-error="{$error}"{/if}>
        <option value="">[[Miscellaneous!Any:raw]] [[FormFieldCaptions!{$levels_captions.$index}:raw]]</option>
        {*{defining parent of the curent selectbox}*}
        {if $index == 0}
            {assign var='parent' value=0}
        {else}
            {assign var='parentIndex' value=$index-1}
            {assign var='parent' value=$value.$parentIndex}
        {/if}
        {*{generating tree items based on parent}*}
        {foreach from=$tree_values.$parent item=tree_value}
            <option value="{$tree_value.sid|escape}"{if $value.$index == $tree_value.sid} selected="selected"{/if}>{tr mode="raw" domain="Property_$id"}{$tree_value.caption}{/tr}</option>
        {/foreach}
    </select>
{/section}
{include file="field_types^tree_js.tpl"}
