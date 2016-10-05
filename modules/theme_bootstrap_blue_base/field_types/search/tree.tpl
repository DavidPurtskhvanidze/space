<div class="row">
    {section name=foo start=0 loop=$tree_depth}
        {assign var="index" value=$smarty.section.foo.index}
        <div class="col-xs-6">
            <select class="form-control searchTreeLevel1" name="{$id}[tree][{$index}]">
                <option value="">[[Miscellaneous!Any:raw]] [[FormFieldCaptions!{$levels_captions.$index}:raw]]</option>
                {*{defining parent of the curent selectbox}*}
                {if $index == 0}
                    {assign var='parent' value=0}
                {else}
                    {assign var='parentIndex' value=$index-1}
                    {assign var='parent' value=$value.tree.$parentIndex}
                {/if}
                {*{generating tree items based on parent}*}
                {foreach from=$tree_values.$parent item=tree_value}
                    <option value="{$tree_value.sid|escape}"{if $value.tree.$index == $tree_value.sid} selected="selected"{/if}>{tr mode="raw" domain="Property_$id"}{$tree_value.caption}{/tr}</option>
                {/foreach}
            </select>
        </div>
    {/section}
    {include file="field_types^tree_js.tpl"}
</div>
