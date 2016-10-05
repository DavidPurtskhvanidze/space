<div class="changeUserGroupForm">
	{if $actionFinished}
		{literal}
		<script type="text/javascript">
			window.location = "{/literal}{page_path id='user_profile'}{literal}";
		</script>
		{/literal}
	{else}
		<div class="dialogFormMessage">
			[[Please add data to required profile fields in order to complete the change of User Group]]:
		</div>
		{display_error_messages}
		<form method="post" action="" enctype="multipart/form-data">
			<table class="form table table-no-border">
				{foreach from=$form_fields item=form_field}
					{if $form_field.is_required}
						<tr>
							<td class="inputFormCaption">[[$form_field.caption]] <span class="asterisk">*</span> : </td>
							<td class="inputFormValue">
								{input property=$form_field.id}
							</td>
						</tr>
					{/if}
				{/foreach}
				<tr>
					<td colspan="2">
						<input type="hidden" name="action" value="change_user_group" />
						<input type="hidden" name="groupId" value="{$groupId}" />
						<input type="submit" value="[[Change User Group:raw]]" class="btn btn-orange h6"/>
					</td>
				</tr>
			</table>
		</form>
	{/if}
</div>
<script type="text/javascript" src="{url file="field_types^showInputError.js"}"></script>
