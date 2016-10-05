{foreach from=$tree_values.$parent item=node}
	{assign var="node_sid" value=$node.sid}
	<option value="{$node.sid}" title="{$node.path}" {if $node.sid eq $selected}selected{/if} >
		{capture name="node_caption"}[[$node.caption:raw]]{/capture}
		{$smarty.capture.node_caption|indent:$node.level:" &middot; "}({$node.listing_number})
	</option>
	{if isset($tree_values.$node_sid)}
		{include file="field_types^search/category_tree_option.tpl" tree_values=$tree_values parent=$node.sid selected=$selected}
	{/if}
{/foreach}
