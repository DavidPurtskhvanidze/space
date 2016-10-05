<select name="{$id}[tree][1]" onChange="location.href='{page_path module='export_listings' function='export_listings'}?{$id}[tree][1]=' + this.value" id="category_tree" class="form-control">
	<option value="0">[[All Categories]]</option>
	{include file="field_types^search/category_tree_option.tpl" tree_values=$tree_values parent=0 selected=$value.tree.1}
</select>
