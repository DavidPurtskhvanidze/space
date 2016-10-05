<select name="{$id}[tree][1]" id="CategoryTree" class="form-control">
    <option value="0">[[Categories!All Categories:raw]]</option>
    {include file="field_types^search/category_tree_option_no_digits.tpl" tree_values=$tree_values parent=0 selected=$value.tree.1}
</select>
