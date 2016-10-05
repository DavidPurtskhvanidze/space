<div class="editTreeValues">
	<div class="breadcrumbs">
	<a href="{page_path id='user_groups'}">[[User Groups]]</a>
	{if $type_info.sid != 0}
	&gt; <a href="{page_path id='edit_user_group'}?sid={$type_sid}">{$type_info.id}</a>
	{/if}
	&gt; <a href="{page_path id='edit_user_profile'}?user_group_sid={$type_info.sid}">[[Edit User Profile Fields]]</a>
	&gt; <a href="{page_path id='edit_user_profile_field'}?sid={$field_sid}&amp;user_group_sid={$type_info.sid}">[[$field_info.caption]]</a>
	{foreach from=$node_info.node_path item=node key=level name=node_path_block}
		 &gt;
		{if !$smarty.foreach.node_path_block.last}
			<a href="?field_sid={$field_sid}&node_sid={$node.sid}">{$node.caption}</a>
		{else}
			[[Edit]] {$node.caption}
		{/if}
	{/foreach}
	</div>

	<h1>[[Edit Tree]]</h1>

	{include file='miscellaneous^field_errors.tpl' errors=$field_errors}

	{if $node_info.sid}
		<div class="editTreeValueForm">
			{assign var=previous_level value=$current_level-1}
			{if $field_info.levels_captions.$previous_level ne ''}
				<h2>[[Edit {$field_info.levels_captions.$previous_level}]]</h2>
			{else}
				<h2>[[Edit Tree Value]]</h2>
			{/if}

			<form method="post">
                {CSRF_token}
				<input type="hidden" name="action" value="save">
				<input type="hidden" name="field_sid" value="{$field_sid}">
				<input type="hidden" name="node_sid" value="{$node_sid}">
				<table class="properties">
					<tr>
						<td>[[Value]]</td>
						<td><span class="asterisk">*</span></td>
						<td><input type="text" name="tree_item_value" value="{$node_info.caption|escape}" class="form-control"></td>
					</tr>
					<tr>
						<td colspan="2">[[Move]]</td>
						<td>
							<div class="radio">
								<label>
									<input type="radio" name="order" value="begin" id="move_order_begin">
									[[to the beginning]]
								</label>
							</div>
							<div class="radio">
								<label>
									<input type="radio" name="order" value="end" id="move_order_end">
									[[to the end]]
								</label>
							</div>
							<div class="radio">
								<label>
									<input type="radio" name="order" value="after" id="move_order_after">
									[[after]]
									<select name="after_tree_item_sid" class="form-control">
										{foreach from=$tree_parent_items item=tree_value key=sid}
											{if $sid != $node_sid}
												<option value="{$sid}">{$tree_value}</option>
											{/if}
										{/foreach}
									</select>
								</label>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="2"></td>
						<td><input type="submit" value="[[Save:raw]]" class="btn btn-default"></td>
					</tr>
				</table>
			</form>
		</div>
	{/if}

	<div class="addTreeValueForm">
		{if $field_info.levels_captions.$current_level ne ''}
			<h2>[[Add a New {$field_info.levels_captions.$current_level}]]</h2>
		{else}
			<h2>[[Add a New Tree Value]]</h2>
		{/if}

		<form method="post">
            {CSRF_token}
			<input type="hidden" name="action" value="add">
			<input type="hidden" name="field_sid" value="{$field_sid}">
			<input type="hidden" name="node_sid" value="{$node_sid}">
			<table class="properties">
				<tr>
					<td>[[Value]]</td>
					<td><span class="asterisk">*</span></td>
					<td><input type="text" name="tree_item_value" class="form-control"></td>
				</tr>
				<tr>
					<td colspan="2"></td>
					<td>
						<div class="radio">
							<label>
								<input type="radio" name="order" value="begin" id="order_begin">
								[[to the beginning]]
							</label>
						</div>
						<div class="radio">
							<label>
								<input type="radio" name="order" value="end" checked id="order_end">
								[[to the end]]
							</label>
						</div>
						<div class="radio">
							<label>
								<input type="radio" name="order" value="after" id="order_after">
								[[after]]
								<select name="after_tree_item_sid" class="form-control">
									{foreach from=$tree_items item=tree_value key=sid}
										<option value="{$sid}">[[$tree_value:raw]]</option>
									{/foreach}
								</select>
							</label>
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="2"></td>
					<td><input type="submit" value="[[Add:raw]]" class="btn btn-default"></td>
				</tr>
			</table>
		</form>
	</div>


	<div class="treeValuesControls">
		<ul>
			<li><a href="{page_path id='edit_user_profile_field_import_tree_data'}?field_sid={$field_sid}">[[Import data from file]]</a></li>
			<li><a href="?field_sid={$field_sid}&node_sid={$node_sid}&action=sort_all_ascending">[[Sort all tree values alphabetically ascending]]</a></li>
		</ul>
	</div>

	<p>
		{foreach from=$node_info.node_path item=node key=level name=node_path_block}
			{if $smarty.foreach.node_path_block.iteration > 1} / {/if}
			<a href="?field_sid={$field_sid}&node_sid={$node.sid}">{$node.caption}</a>
		{/foreach}
	</p>

{display_success_messages}

    <form method="post" action="" name="itemSelectorForm">
    {CSRF_token}

	<table class="items sortable" data-parent-node-value="{$node_sid}" data-parent-value="{$field_sid}" data-sorting-url="{page_path id='edit_user_profile_field_edit_tree'}">
		<tr class="head">
            <th>
                <div class="multilevelMenu">
                    <ul>
                        <li>
                            <a href="#" class="caption">[[Mass Actions]]</a>
                            <ul class="actionList">
                                <li><a href="#" class="checkAll">[[Check all]]</a></li>
                                <li><a href="#" class="uncheckAll">[[Uncheck all]]</a></li>
                                <li><a href="?field_sid={$field_sid}&node_sid={$node_sid}&action=sort_ascending">[[Sort values alphabetically ascending]]</a></li>
                                <li><a href="?field_sid={$field_sid}&node_sid={$node_sid}&action=sort_descending">[[Sort values alphabetically descending]]</a></li>
                                <li><a class="deleteSelected" href="?field_sid={$field_sid}&node_sid={$node_sid}&action=delete_selected_items">[[Delete selected values]]</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </th>
			<th>[[Tree Values]]</th>
			<th colspan="3">[[Actions]]</th>
		</tr>
		{foreach from=$tree_items item=tree_value key=sid name=items_block}
			<tr class="editableContainer {cycle values='odd,even'}" data-item-sid="{$sid}">
                <td class="checkboxCell"><input type="checkbox" name="values[]" value="{$sid}"/></td>
				<td>[[$tree_value]]</td>
				<td><a class="itemControls edit" href="?field_sid={$field_sid}&node_sid={$sid}" title="[[Edit:raw]]">[[Edit]]</td>
				<td><a class="itemControls delete" href="?field_sid={$field_sid}&node_sid={$node_sid}&action=delete&item_sid={$sid}" onclick="return confirm('[[Are you sure you want to delete this?:raw]]')" title="[[Delete:raw]]">[[Delete]]</td>
				<td class="sort">
					<span title="[[Drag and drop to change the order:raw]]">&nbsp;</span>
				</td>
			</tr>
		{/foreach}
	</table>
    </form>
</div>

<script type="text/javascript">
var areYouSureMessage = '[[Are you sure?:raw]]';//[[Are you sure?:raw]]
var noSelectedItemsMessage = "[[You have not selected any items. Please select one or more items and proceed with actions.:raw]]";

{literal}
$(document).ready(function(){

    $(".uncheckAll").click(function(){
        $(this).parents('form').find("input[name^=values]").prop("checked", false);
        $(this).parents("li").children("a.caption").click();
        return false;
    });
    $(".checkAll").click(function(){
        $(this).parents('form').find("input[name^=values]").prop("checked", true);
        $(this).parents("li").children("a.caption").click();
        return false;
    });
    $(".deleteSelected").click(function () {
        if (($(".editableContainer :input:checked").size()) > 0) {
            var conf = confirm(areYouSureMessage);
            if (conf) {
                window.location.href = $(this).attr("href") + "&" + $("form[name='itemSelectorForm']").serialize();

            }
        } else {
            alert(noSelectedItemsMessage);
        }
        return false;
    });
});
</script>

{/literal}
{include file="miscellaneous^multilevelmenu_js.tpl"}
{include file="miscellaneous^sortable_js.tpl"}
