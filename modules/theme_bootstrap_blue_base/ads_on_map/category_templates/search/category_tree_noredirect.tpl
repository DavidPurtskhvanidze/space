{if (($tree_values.0|count)>1||($tree_values|count)>1)}<select name="{$id}[tree][1]" id="CategoryTree" class="searchCategorySelectbox form-control">
    <option value="0">[[Categories!All Categories:raw]]</option>
    {include file="field_types^search/category_tree_option.tpl" tree_values=$tree_values parent=0 selected=$value.tree.1}
</select>
{/if}
