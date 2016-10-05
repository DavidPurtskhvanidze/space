<div class="editProfilePage">

    <div class="container">
        <h1 class="page-title">[[My Profile]]</h1>

        {include file="errors.tpl" errors=$errors}

        {display_success_messages}
        {display_error_messages}
    </div>

    <div class="bg-grey">
        <div class="container">
            <form method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">
                {assign var=parts value=2}
                {math equation="ceil(values / parts)" assign=part_items values=$form_fields|@count parts=$parts}
                <div class="row">
                    {section name=part loop=$parts}
                        {assign var=start value=$part_items*$smarty.section.part.index}
                        <div class="col-md-6 col-sm-8 col-sm-offset-2 col-md-offset-0">
                            {foreach from=$form_fields|@array_slice:$start:$part_items item=form_field}
                                <div class="form-group {$form_field.type}">
                                    <label class="col-sm-3 control-label">
                                        {if $form_field.type !== 'boolean'}
                                            [[$form_field.caption]]&nbsp;
                                        {/if}
                                        {if $form_field.is_required}<span class="asterisk">*</span>{/if}
                                    </label>

                                    <div class="col-sm-9 col-md-7">
                                        {if $form_field.id == 'username'}
                                            <p class="form-control-static">
                                                {input property=$form_field.id}
                                                <a onclick='return openDialogWindow("[[Change username:raw]]", this.href, 400, true)' href="{page_path module='users' function='change_username'}">
                                            <span>
                                                [[Change username]]
                                            </span>
                                                </a>
                                            </p>
                                        {else}
                                            {input property=$form_field.id}
                                        {/if}
                                    </div>
                                </div>

                                {if $form_field.id == 'password'}
                                    <div class="form-group {$form_field.type}">
                                        <label class="col-sm-3 control-label">
                                            [[User Group]]
                                        </label>

                                        <div class="col-sm-9 form-control-static">
                                            [[$userGroupInfo.name]]
                                            <a onclick="return openDialogWindow('[[Change User Group:raw]]', this.href, 450, true)" href="{page_path module='users' function='change_user_group'}" class="action changeUserGroup">
                                        <span>
                                           [[Change User Group]]
                                        </span>
                                            </a>
                                        </div>
                                    </div>
                                {/if}
                            {/foreach}
                        </div>
                    {/section}
                </div>


                <div class="form-group text-center">
                    <div class="space-20"></div>
                    <input type="hidden" name="action" value="save_info"/>
                    <button type="submit" class="btn btn-orange h5">&nbsp;&nbsp;&nbsp;&nbsp;[[Save:raw]]&nbsp;&nbsp;&nbsp;&nbsp;</button>
                </div>

            </form>
            <div class="clearfix"></div>
            <div class="space-20"></div>
            <div class="space-20"></div>
            <div class="space-20"></div>
            {module name="listing_repost" function="display_profile_settings"}
            <div class="space-20"></div>
            <div class="space-20"></div>
            <div class="space-20"></div>
        </div>
    </div>


	{require component="jquery" file="jquery.js"}
	{require component="jquery-maxlength" file="jquery.maxlength.js"}
	{require component="js" file="script.maxlength.js"}
	{include file="miscellaneous^dialog_window.tpl"}

</div>
<script type="text/javascript" src="{url file="field_types^showInputError.js"}"></script>
