<select class="searchTreeLevel2" name="{$id}[tree][1]"  id="{$id}[tree][1]">
    <option value="">[[Miscellaneous!Any:raw]] [[FormFieldCaptions!{$levels_captions.1}:raw]]</option>
    {assign var='parent' value=$value.tree.0}
    {foreach from=$tree_values.$parent item=tree_value}
    <option value="{$tree_value.sid|escape}"{if $value.tree.1 == $tree_value.sid} selected="selected"{/if}>{tr mode="raw" domain="Property_$id"}{$tree_value.caption}{/tr}</option>
    {/foreach}
</select><br />
