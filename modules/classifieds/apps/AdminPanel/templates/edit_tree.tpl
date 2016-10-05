<div class="editTreeValues">
    <div class="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                {foreach from=$ancestors item=ancestor}
                    <a href="{page_path id='edit_category'}?sid={$ancestor.sid}">[[$ancestor.caption]]</a> &gt;
                {/foreach}
                <a href="{page_path id='edit_category_field'}?sid={$field_sid}">[[$field_info.caption]]</a>
                {foreach from=$node_info.node_path item=node key=level name=node_path_block}
                     &gt;
                    {if !$smarty.foreach.node_path_block.last}
                        <a href="?field_sid={$field_sid}&node_sid={$node.sid}">{$node.caption}</a>
                    {else}
                        [[Edit]] {$node.caption}
                    {/if}
                {/foreach}
            </li>
        </ul>
    </div>
    <div class="page-content">
        <div class="page-header">
            <h1 class="lighter">[[Edit Tree]]</h1>
        </div>

	{include file='miscellaneous^field_errors.tpl' errors=$field_errors}

	{if $node_info.sid}
		<div class="editTreeValueForm">
			{assign var=previous_level value=$current_level-1}
			{if $field_info.levels_captions.$previous_level ne ''}
				<h1 class="lighter">[[Edit {$field_info.levels_captions.$previous_level}]]</h1>
			{else}
				<h1 class="lighter">[[Edit Tree Value]]</h1>
			{/if}
            <div class="row">
			<form method="post" class="form-horizontal" role="form">
                {CSRF_token}
				<input type="hidden" name="action" value="save">
				<input type="hidden" name="field_sid" value="{$field_sid}">
				<input type="hidden" name="node_sid" value="{$node_sid}">
                <div class="form-group">
                    <label class="col-sm-1 control-label">
                      [[Value]]
                      <i class="icon-asterisk smaller-60"></i>
                    </label>
                    <div class="col-sm-8">
                        <input type="text" name="tree_item_value" value="{$node_info.caption|escape}" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-1 control-label">
                      [[Move]]
                      <i class="icon-asterisk smaller-60"></i>
                    </label>
                    <div class="col-sm-8">
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
                    </div>
                </div>
                <div class="clearfix form-actions">
                   <input type="submit" value="[[Save:raw]]" class="btn btn-default">
                </div>
			</form>
		</div>
		</div>
	{/if}

	<div class="addTreeValueForm">
		{if $field_info.levels_captions.$current_level ne ''}
			<h1 class="lighter">[[Add a New {$field_info.levels_captions.$current_level}]]</h1>
		{else}
			<h1 class="lighter">[[Add a New Tree Value]]</h1>
		{/if}

		<div class="row">
			<form method="post" class="form-horizontal" role="form">
            {CSRF_token}
			<input type="hidden" name="action" value="add">
			<input type="hidden" name="field_sid" value="{$field_sid}">
			<input type="hidden" name="node_sid" value="{$node_sid}">
            <div class="form-group">
                <label class="col-sm-1 control-label">
                  [[Value]]
                  <i class="icon-asterisk smaller-60"></i>
                </label>
                <div class="col-sm-8">
                    <input type="text" name="tree_item_value" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-1 control-label">
                </label>
                <div class="col-sm-8">
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
                </div>
            </div>
            <div class="clearfix form-actions">
               <input type="submit" value="[[Add:raw]]" class="btn btn-default">
            </div>
		</form>
	</div>
	</div>
    <a class="btn btn-link" href="{page_path id='import_tree_data'}?field_sid={$field_sid}">[[Import data from file]]</a><br />
    <a class="btn btn-link" href="?field_sid={$field_sid}&node_sid={$node_sid}&action=sort_all_ascending">[[Sort all tree values alphabetically ascending]]</a><br />
	{foreach from=$node_info.node_path item=node key=level name=node_path_block}
        {if $smarty.foreach.node_path_block.iteration > 1} / {/if}
        <a class="btn btn-link" href="?field_sid={$field_sid}&node_sid={$node.sid}">{$node.caption}</a>
    {/foreach}
	

{display_success_messages}

    <div class="col-xs-12">
        <div class="table-responsive">
            <div class="dataTables_wrapper" role="grid">
                <form method="post" action="" name="itemSelectorForm">
                    {CSRF_token}
                    <div class="row">
                        <div class="col-sm-6 massAction">
                            <a href="#" class="btn btn-xs dropdown-toggle btn-primary actionWithSelected" data-toggle="dropdown">
                                [[Mass Actions]]
                                <i class="icon-angle-down icon-on-right"></i>
                            </a>
                            <ul class="dropdown-menu actionList">
                                <li><a href="#" class="checkAll">[[Check all]]</a></li>
                                <li><a href="#" class="uncheckAll">[[Uncheck all]]</a></li>
                                <li><a href="?field_sid={$field_sid}&node_sid={$node_sid}&action=sort_ascending">[[Sort values alphabetically ascending]]</a></li>
                                <li><a href="?field_sid={$field_sid}&node_sid={$node_sid}&action=sort_descending">[[Sort values alphabetically descending]]</a></li>
                                <li><a class="deleteSelected" href="?field_sid={$field_sid}&node_sid={$node_sid}&action=delete_selected_items">[[Delete selected values]]</a></li>
                            </ul>
                        </div>
                    </div>
                    <table class="items sortable table table-striped table-hover" data-parent-node-value="{$node_sid}" data-parent-value="{$field_sid}" data-sorting-url="{page_path id='edit_listing_field_edit_tree'}">
                        <thead>
                        <tr class="head">
                            <th class="center">
                                <label>
                                    <input class="ace" type="checkbox" />
                                    <span class="lbl"></span>
                                </label>
                            </th>
                            <th>[[Tree Values]]</th>
                            <th colspan="2">[[Actions]]</th>
                        </tr>
                        </thead>
                        <tbody>
                        {foreach from=$tree_items item=tree_value key=sid name=items_block}
                            <tr class="editableContainer {cycle values='odd,even'}" data-item-sid="{$sid}">
                                <td class="checkboxCell center">
                                    <label>
                                        <input class="ace" type="checkbox" name="values[]" value="{$sid}"/>
                                        <span class="lbl"></span>
                                    </label>
                                </td>
                                <td>[[$tree_value]]</td>
                                <td>
                                    <div class="btn-group">
                                        <a class="btn btn-xs btn-info itemControls edit" href="?field_sid={$field_sid}&node_sid={$sid}" title="[[Edit:raw]]">
                                            <i class="icon-edit bigger-110"></i>
                                        </a>
                                        <a class="btn btn-xs btn-danger itemControls delete" href="?field_sid={$field_sid}&node_sid={$node_sid}&action=delete&item_sid={$sid}" onclick="return confirm('[[Are you sure you want to delete this page?:raw]]')" title="[[Delete:raw]]">
                                            <i class="icon-trash bigger-110"></i>
                                        </a>
                                    </div>
                                </td>
                                {*<td><a class="itemControls edit" href="?field_sid={$field_sid}&node_sid={$sid}" title="[[Edit:raw]]">[[Edit]]</td>*}
                                {*<td><a class="itemControls delete" href="?field_sid={$field_sid}&node_sid={$node_sid}&action=delete&item_sid={$sid}" onclick="return confirm('[[Are you sure you want to delete this?:raw]]')" title="[[Delete:raw]]">[[Delete]]</td>*}
                                <td class="sort">
                                    <span title="[[Drag and drop to change the order:raw]]">
                                        <i class="icon-sort"></i>
                                    </span>
                                </td>
                            </tr>
                        {/foreach}
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>


</div>
</div>

<script type="text/javascript">
var areYouSureMessage = '[[Are you sure?:raw]]';//[[Are you sure?:raw]]
var noSelectedItemsMessage = "[[You have not selected any items. Please select one or more items and proceed with actions.:raw]]";

{literal}
$(document).ready(function(){

    function menuClose(){
        var menuContainer = $(".massAction");
        if (menuContainer.hasClass('open'))
        {
            menuContainer.removeClass('open');
        }
    }

    $('table th input:checkbox').on('click' , function(){
        var that = this;
        $(this).closest('table').find('tr > td:first-child input:checkbox')
                .each(function(){
                    this.checked = that.checked;
                    $(this).closest('tr').toggleClass('selected');
                });
        menuClose();
    });

    $(".uncheckAll").click(function(){
        $(this).parents('form').find("input[name^=values]").prop("checked", false);
        $(this).parents("li").children("a.caption").click();
        $('table th input:checkbox').click();
        menuClose();
        return false;
    });
    $(".checkAll").click(function(){
        $(this).parents('form').find("input[name^=values]").prop("checked", true);
        $(this).parents("li").children("a.caption").click();
        $('table th input:checkbox').click();
        menuClose();
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
        menuClose();
        return false;
    });
});
</script>

{/literal}
{include file="miscellaneous^multilevelmenu_js_custom.tpl"}
{include file="miscellaneous^sortable_js.tpl"}
