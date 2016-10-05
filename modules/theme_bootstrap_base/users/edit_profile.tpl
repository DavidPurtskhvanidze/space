<div class="editProfilePage">

	<h1>[[My Profile]]</h1>

	{include file="errors.tpl" errors=$errors}

	{display_success_messages}
	{display_error_messages}

	<form method="post" action="" enctype="multipart/form-data" class="form-horizontal" role="form">

		{foreach from=$form_fields item=form_field}

			<div class="form-group">
				<label class="col-sm-2 control-label">
					[[$form_field.caption]] {if $form_field.is_required}<span class="asterisk">*</span>{/if}
				</label>

				<div class="col-sm-10">
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
				<div class="form-group">
					<label class="col-sm-2 control-label">
						[[User Group]]
					</label>

					<div class="col-sm-10 form-control-static">
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

		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">

				<input type="hidden" name="action" value="save_info"/>

				<button type="submit" class="btn btn-default">[[Save:raw]]</button>
			</div>
		</div>

	</form>

	{module name="listing_repost" function="display_profile_settings"}

	{require component="jquery" file="jquery.js"}
	{require component="jquery-maxlength" file="jquery.maxlength.js"}
	{require component="js" file="script.maxlength.js"}
	{include file="miscellaneous^dialog_window.tpl"}

</div>
<script type="text/javascript" src="{url file="field_types^showInputError.js"}"></script>
