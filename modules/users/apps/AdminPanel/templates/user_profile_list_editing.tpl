<div class="editListValues">

    <div class="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <a href="{page_path id='user_groups'}">[[User Groups]]</a>
                {if $type_sid != 0}&gt; <a href="{page_path id='edit_user_group'}?sid={$type_sid}">{$type_info.id}</a>{/if}
                &gt; <a href="{page_path id='edit_user_profile'}?user_group_sid={$type_sid}">[[Edit User Profile Fields]]</a>
                &gt; <a href="{page_path id='edit_user_profile_field'}?sid={$field_info.sid}&user_group_sid={$type_sid}">{$field_info.caption}</a>
                &gt; [[Edit List]]
            </li>
        </ul>
	</div>

    <div class="page-content">
        <div class="page-header">
            <h1 class="lighter">[[Edit List]]</h1>
        </div>

        {if $error eq 'LIST_VALUE_IS_EMPTY'}
            <p class="error">[['Value' is empty.]]</p>
        {elseif $error eq 'LIST_VALUE_ALREADY_EXISTS'}
            <p class="error">[[This value is already used.]]</p>
        {/if}

        <h4 class="headerBlue">[[Add a New List Value]]</h4>

        <div class="row">

            <div class="alert alert-info">[[Fields marked with an asterisk (<span class="asterisk">*</span>) are mandatory]]</div>

            <form method="post" class="form-horizontal" role="form">
                {CSRF_token}
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="field_sid" value="{$field_sid}">

                <div class="form-group">
                    <label class="col-sm-2 control-label">
                        [[Value]]
                        <i class="icon-asterisk smaller-60"></i>
                    </label>
                    <div class="col-sm-8">
                        <input type="text" name="list_item_value" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">
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

                            </label>
                            <select name="after_item_sid" onclick="document.getElementById('move_order_after').checked = true">
                                {foreach from=$list_items item=list_value key=sid}
                                    <option value="{$sid}">{$list_value}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                </div>
                <div class="clearfix form-actions">
                    <input type="submit" value="[[Add:raw]]" class="btn btn-default" />
                </div>
            </form>

            <a class="btn btn-link" href="{page_path module='users' function='import_list_data'}?field_sid={$field_sid}">[[Import data from file]]</a>

            {display_success_messages}

            <form method="post"  class="form-horizontal" role="form" action="" name="itemSelectorForm">
                {CSRF_token}
                <div class="row">
                    <div class="col-xs-12 usersBlock">
                        <table class="items sortable  table table-striped table-hover" data-parent-value="{$field_sid}" data-sorting-url="{page_path id='edit_user_profile_field_edit_list'}">

                            <thead>
                                <tr class="head">
                                    <th>
                                        <div class="multilevelMenu" style="width:150px !important;">
                                            <ul>
                                                <li>
                                                    <div class="slideToggler2">
                                                        <a href="#" class="caption"><b class="arrow icon-angle-down"></b> [[Mass Actions]]</a>
                                                    </div>
                                                    <ul class="slideContent2">
                                                        <li><a href="#" class="checkAll">[[Check all]]</a></li>
                                                        <li><a href="#" class="uncheckAll">[[Uncheck all]]</a></li>
                                                        <li><a href="?field_sid={$field_sid}&action=sort_ascending">[[Sort values alphabetically ascending]]</a></li>
                                                        <li><a href="?field_sid={$field_sid}&action=sort_descending">[[Sort values alphabetically descending]]</a></li>
                                                        <li><a class="deleteSelected" href="?field_sid={$field_sid}&action=delete_selected_items">[[Delete selected values]]</a></li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                    </th>
                                    <th>[[List Values]]</th>
                                    <th colspan="3">[[Actions]]</th>
                                </tr>
                            </thead>

                            <tbody>
                                {foreach from=$list_items item=list_value key=sid name=items_block}
                                <tr class="editableContainer {cycle values="odd,even"}" data-item-sid="{$sid}" id="{$sid}">

                                    <td class="checkboxCell"><input type="checkbox" name="values[]" value="{$sid}" /></td>

                                    <td>
                                        <div class="editableCaption">[[$list_value]]</div>
                                        <div class="spinner" style="display: none;"><img src="{url file='main^spinner.gif'}" /></div>
                                    </td>

                                    <td>
                                        <div class="btn-group">
                                            <a href="#"  class="itemControls edit btn btn-xs btn-info" title="Edit" data-rel="tooltip" data-original-title="[[Edit]]">
                                                <i class="icon-edit bigger-120"></i>
                                            </a>
                                            <a class="itemControls delete btn btn-xs btn-danger" href="?field_sid={$field_sid}&action=delete&item_sid={$sid}" onclick="return confirm('[[Are you sure?:raw]]')" title="[[Delete:raw]]" data-rel="tooltip" data-original-title="[[Delete]]">
                                                <i class="icon-trash bigger-120"></i>
                                            </a>
                                        </div>
                                    </td>

                                    <td></td>

                                    <td class="sort">
                                        <span title="[[Drag and drop to change the order:raw]]"><i class="icon-sort"></i></span>
                                    </td>
                                </tr>
                            {/foreach}
                            </tbody>
                        </table>
                    </div>
                </div>

            </form>
            <div id="errorMessageDialog" title="[[Field value error:raw]]" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
	            <div class="modal-dialog modal-sm">
		            <div class="modal-content">
			            <div class="alert alert-danger" role="alert">
				            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				            <p></p>
			            </div>
		            </div>
	            </div>
            </div>

            {require component="jquery" file="jquery.js"}
            {require component="jquery-ui" file="jquery-ui.js"}
            {require component="jquery-ui" file="css/smoothness/jquery-ui.css"}

            <script type="text/javascript">

	            var saveButtonCaption = '[[Save:raw]]';
                var editCaptionUrl = "{page_path id='edit_user_profile_field_edit_list_item'}";
                var fieldSid = {$field_sid};
                var areYouSureMessage = '[[Are you sure?:raw]]';//[[Are you sure?:raw]]
                var noSelectedItemsMessage = "[[You have not selected any items. Please select one or more items and proceed with actions.:raw]]";
                {literal}
                $(document).ready(function(){
	                $('.slideToggler2').click(function () {
		                $('.slideContent2').slideToggle('slow');
		                $(this).toggleClass('slideSign2');
		                return false;
	                });
	                $('.collaps2').click(function () {
		                $('.slideContent2').slideToggle('slow');
		                $('.slideToggler2').toggleClass('slideSign2');
		                return false;
	                });

	                $('#errorMessageDialog')
			                .modal({
				                show: false
			                })
			                .find('.alert').on('close.bs.alert', function () {
				                $('#errorMessageDialog').modal('hide');
				                return false;
			                });

                    $(".itemControls.edit").click(function() {
                        if ($(this).hasClass('disabled'))
                        {
                            return false;
                        }
                        $(this).parents("tr.editableContainer").find("div.editableCaption").fadeOut(300, function() {showInputBoxAndSaveButton(this);});
                        $(this).parents("tr.editableContainer").find("div.editLink").fadeOut(300);
                        $(this).parents("tr.editableContainer").find("div.editableCaption").fadeIn(300);
                        $(this).addClass('disabled');
                        return false;
                    });

                    function showInputBoxAndSaveButton(target)
                    {
                        var value = $(target).html();
                        $(target).html($('<form/>',{
                            'class': "editCaptionForm",
                            submit: function() {
                                $(this).parents("div.editableCaption").fadeOut(300, function(){
                                    $(target).parents("tr.editableContainer").find('.itemControls.edit').removeClass('disabled');
                                    hideInputBoxAndSaveCaption(this, value)
                                });
                                $(this).parents("div.editableCaption").fadeIn(300);
                                return false;
                            }
                        }));
                        $(target).children("form.editCaptionForm").append($('<input/>', {
                            type: 'text',
                            'class': 'editCaptionInputBox',
                            value: value
                        }));
                        $(target).find("input.editCaptionInputBox").focus();
                        $(target).children("form.editCaptionForm").append($('<input/>', {
                            type: 'submit',
                            value: saveButtonCaption,
                            'class': 'saveCaptionButton'
                        }));
                    }

                    function hideInputBoxAndSaveCaption(target, oldValue)
                    {
                        var newValue = $(target).find("input.editCaptionInputBox").val();
                        var objectId = $(target).parents("tr.editableContainer").attr("id");

                        $(target).parents("tr.editableContainer").find("div.spinner").show();
                        $(target).hide();

                        $.ajax({
                            type: "POST",
                            url: editCaptionUrl,
                            data: {field_sid: fieldSid, item_sid: objectId, list_item_value: newValue},
                            success: function(){
                                $(target).html(newValue);
                                $(target).parents("tr.editableContainer").find("div.spinner").hide();
                                $(target).show();
                                $(target).parents("tr.editableContainer").find("div.editLink").fadeIn(300);
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
	                            $('#errorMessageDialog').find('.alert p').text(jqXHR.responseText);
	                            $('#errorMessageDialog').modal('show');

                                $(target).find("input.editCaptionInputBox").val(oldValue);
                                $(target).parents("tr.editableContainer").find("div.spinner").hide();
                                $(target).show();
                            }
                        });
                    }

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
                {/literal}
            </script>
            {include file="miscellaneous^multilevelmenu_js.tpl"}
        </div>
    </div>
</div>

{include file="miscellaneous^sortable_js.tpl"}
