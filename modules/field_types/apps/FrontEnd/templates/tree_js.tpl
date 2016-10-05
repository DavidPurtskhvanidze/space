{require component="jquery" file="jquery.js"}
{require component="js" file="TreeController.js"}
<script type="text/javascript">
	$(document).ready(function()
	{ldelim}
		var treeData = {ldelim}
		{foreach from=$tree_values key=parent_id item=tree_node_values name=tree_block}
			{$parent_id}:{strip}
			{ldelim}
			{foreach from=$tree_node_values item=tree_value name=tree_node_block}
				{capture name=itemCaption}{tr mode="raw" domain="Property_$id"}{$tree_value.caption}{/tr}{/capture}
				{$smarty.foreach.tree_node_block.iteration} : {ldelim}'value' : '{$tree_value.id}', 'caption' : '{$smarty.capture.itemCaption|addcslashes:"\'\\\/"}'{rdelim}{if $smarty.foreach.tree_node_block.iteration < $smarty.foreach.tree_node_block.total},{/if}
			{/foreach}
			{rdelim}{if $smarty.foreach.tree_block.iteration < $smarty.foreach.tree_block.total},{/if}{/strip}
		{/foreach}
		{rdelim};

		var fieldId = '{$id}';
		document.globalTreeControlRegistry.bindTreeData(fieldId, treeData);
		$("select[name^='{$id}\[']").each(function()
		{literal}
		{
			document.globalTreeControlRegistry.bindControl(fieldId, this);
		});
		{/literal}
	{rdelim});
</script>
