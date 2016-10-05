{function name=treeOptions level=0 parent=0}
	{foreach $tree_values.$parent as $entry}
		<option value="{$entry.sid}" {if $entry.sid == $selected}selected="selected"{/if}>{"&nbsp;"|str_repeat:$level} [[$entry.caption]]</option>
		{treeOptions tree_values=$tree_values parent=$entry.sid level=$level+1 selected=$selected}
	{/foreach}
{/function}

<select name='{$id}' class="form-control {if $hasError}has-error tooltip-error{/if}" {if $hasError}data-rel="tooltip" data-placement="top" title="{$error}"{/if}>
	<option value="">[[Miscellaneous!Select:raw]] [[FormFieldCaptions!{$caption}:raw]]</option>
	{treeOptions tree_values=$tree_values parent=0 selected=$value|array_pop}
</select>
