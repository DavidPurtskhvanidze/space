<select name="{$id}[tree][1]" id="CategoryTree" data-ajax-reload="false" class="form-control">
	<option value="0">[[Categories!All Categories:raw]]</option>
	{include file="classifieds^refine_search/category_tree_option.tpl" tree_values=$tree_values parent=0 selected=$value.tree.1}
</select>
