<script type="text/javascript">
var siteUrl = '{$GLOBALS.site_url}';
{literal}
function changeSearchForm(category_select)
{
	document.location = siteUrl + "/search" + category_select.options[category_select.selectedIndex].title;
}
{/literal}
</script>
<select name="{$id}[tree][1]" onchange="javascript:changeSearchForm(this);" id="CategoryTree" class="form-control">
	<option value="0">[[Categories!All Categories:raw]]</option>
	{include file="field_types^search/category_tree_option.tpl" tree_values=$tree_values parent=0 selected=$value.tree.1}
</select>
