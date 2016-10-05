<div class="editProfilePage">

<h1>[[My Profile]]</h1>

{include file="errors.tpl" errors=$errors}

{display_success_messages}
{display_error_messages}

<form method="post" action="" enctype="multipart/form-data">
	<table class="form">
		{foreach from=$form_fields item=form_field}
			<tr>
				<td class="inputFormCaption">[[$form_field.caption]] {if $form_field.is_required} <span class="asterisk">*</span>{/if}</td>
				<td class="inputFormValue">
					{input property=$form_field.id}
					{if $form_field.id == 'username'}<br />
						<a onclick='return openDialogWindow("[[Change username:raw]]", this.href, 400, true)' href="{page_path module='users' function='change_username'}">[[Change username]]</a>
					{/if}
				</td>
			</tr>
			{if $form_field.id == 'password'}
				<tr>
					<td class="inputFormCaption">[[User Group]]:</td>
					<td class="inputFormValue">
						[[$userGroupInfo.name]]<br />
						<a onclick="return openDialogWindow('[[Change User Group:raw]]', this.href, 400, true)" href="{page_path module='users' function='change_user_group'}" class="action changeUserGroup">[[Change User Group]]</a>
					</td>
				</tr>
			{/if}
		{/foreach}
		<tr>
			<td>&nbsp;</td>
			<td>
				<input type="hidden" name="action" value="save_info" />
				<input type="submit" value="[[Save:raw]]" class="button" />
			</td>
		</tr>
	</table>
</form>

{module name="listing_repost" function="display_profile_settings"}

{require component="jquery" file="jquery.js"}
{require component="jquery-maxlength" file="jquery.maxlength.js"}
{require component="js" file="script.maxlength.js"}
{include file="miscellaneous^dialog_window.tpl"}

</div>
