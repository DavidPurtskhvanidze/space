<select name="{$id}[tree][1]" id="{$id}" class="searchCategorySelectbox">
	<option value="0">[[Categories!All Categories:raw]]</option>
	{include file="field_types^search/category_tree_option.tpl" tree_values=$tree_values parent=0 selected=$value.tree.1}
</select>
