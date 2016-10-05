<div class="changeUserGroupForm">
	<form action="">
        {CSRF_token}
		<table class="properties table table-no-border">
			{if $userContractId}
				<tr>
					<td colspan="2">
						<div class="alert alert-warning">
							[[Warning: If you changed User Group you may lose subscription. The system will ask you to subscribe to a new Membership Plan next time you post a listing.]]
						</div>
					</td>
				</tr>
			{/if}
			<tr>
				<td>[[Current User Group]]:</td>
				<td>[[$userGroupInfo.name]]</td>
			</tr>
			<tr>
				<td>[[Change to]]:</td>
				<td>
					<select name="groupId" class="form-control">
						{foreach from=$userGroupOptions item=group}
							{if $group.id != $userGroupInfo.id}
								<option value='{$group.id}'>[[$group.name]]</option>
							{/if}
						{/foreach}
					</select>
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<input type="hidden" name="action" value="change_user_group" />
					<input type="hidden" name="formIsShownFirstTime" value="1" />
					<input type="submit" value="[[Submit:raw]]" class="btn btn-default"/>
				</td>
			</tr>
		</table>
	</form>
</div>
