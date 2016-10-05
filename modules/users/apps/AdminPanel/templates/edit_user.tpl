<div class="editUser">
	<div class="breadcrumbs">
		<ul class="breadcrumb">
			<li><a href="{page_path id='users'}?action=restore">[[Users]]</a></li>
			<li>[[Edit User Info]]</li>
		</ul>
	</div>

	<div class="page-content">
		<div class="page-header">
			<h1 class="lighter">[[Edit User Info]]</h1>
		</div>

		<ul class="list-inline">
			<li>
				{if $user.active.isTrue}
					<a class="itemControls deactivate btn btn-link" href="{page_path id='users'}?action=deactivate&users[{$user.sid}]={$user.sid}&{$returnBackUrlParam}">
						<button class="btn btn-yellow">[[Deactivate]]</button>
					</a>
				{else}
					<a class="itemControls activate btn btn-link" href="{page_path id='users'}?action=activate&users[{$user.sid}]={$user.sid}&{$returnBackUrlParam}">
						<button class="btn btn-success">[[Activate]]</button>
					</a>
				{/if}
			</li>
			<li>
				{if $user.trusted_user.isTrue}
					<a class="itemControls activate btn btn-link" href="{page_path id='users'}?action=Make+Untrusted&users[{$user.sid}]={$user.sid}&{$returnBackUrlParam}">
						<button class="btn btn-warning">[[Make Untrusted]]</button>
					</a>
				{else}
					<a class="itemControls deactivate btn btn-link" href="{page_path id='users'}?action=Make+Trusted&users[{$user.sid}]={$user.sid}&{$returnBackUrlParam}">
						<button class="btn btn-info">[[Make Trusted]]</button>
					</a>
				{/if}
			</li>
			<li>
                <a class="itemControls send btn btn-link" href="{page_path id='users'}?action=Send+Activation+Letter&users[{$user.sid}]={$user.sid}&{$returnBackUrlParam}">
					<button class="btn btn-purple">[[Send Activation Letter]]</button>
				</a>
            </li>
			<li>
                <a class="itemControls numberOfListings btn btn-link" href="{$GLOBALS.site_url}/manage_listings/?action=search&amp;username[like]={$user.username}">
					<button class="btn btn-light">{$numberOfListings} [[Listings]]</button>
				</a>
            </li>
            <li>
                <a class="itemControls send btn btn-link"
                   href="{page_path id='users'}?action=Send+Letter&users[{$user.sid}]={$user.sid}&{$returnBackUrlParam}"
                   onclick="return displayPopupForm('#sendLetterForm', this, '[[Send Letter]]')">
                    <button class="btn btn-primary">[[Send Letter]]</button>
                </a>
            </li>
		</ul>
		<br/>
		<br/>

		<div class="row">
			{if $userContractDeleted}
				<div class="success alert alert-success alert-dismissable" role="alert">
					<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					[[User's subscription was successfully deleted.]]
				</div>
			{/if}

			{display_success_messages}
			{display_warning_messages}
			{display_error_messages}
			{$returnBackUrlParam="returnBackUri="|cat:("/edit_user/?username="|cat:$user.username|urlencode)}





			<form class="form form-horizontal" method="post" enctype="multipart/form-data">
				<input type="hidden" name="action" value="save_info">
				<input type="hidden" name="current_username" value="{$user.username}">

				{foreach from=$form_fields item=form_field}
					<div class="form-group">
						<label class="control-label col-sm-2">
							[[$form_field.caption]]
							{if $form_field.is_required}<i class="icon-asterisk smaller-60 "></i>{/if}
						</label>

						<div class="col-sm-6">
							{input property=$form_field.id}
						</div>
					</div>
					{if $form_field.id == 'password'}
						<div class="form-group UserGroup">
							<label class="control-label col-sm-2">
								[[User Group]]:
							</label>

							<div class="col-sm-6">
								<div>[[$userGroupInfo.name]]</div>
								&nbsp;{*&users[]={$user.sid}*}
								<a class="btn btn-xs btn-inverse action changeUserGroup" onclick="return displayPopupForm('.changeUserGroupForm', this, '[[Change User Group:raw]]');" href="{page_path id='users'}?action=CHANGE+USER+GROUP"
								   class="action changeUserGroup">[[Change User Group]]</a>
							</div>
						</div>
					{/if}
				{/foreach}
				<div class="clearfix form-actions">
					<input type="submit" class="btn btn-default" value="[[Save:raw]]">
				</div>
			</form>

			<div class="col-xs-12">
				<form method="post" class="form" id="deleteContactForm">
                    {CSRF_token}
					<input type="hidden" name="action" value="save_info">
					<input type="hidden" name="username" value="{$user.username}">
					<input type="hidden" name="action" value="delete_contract">
					<input type="submit" class="btn btn-danger" value="[[Delete User's Subscription:raw]]" onclick="return confirm('[[Do you really want to delete user&apos;s subscription?:raw]]')" class="form-control">

					<div class="space-10"></div>
					<div class="alert alert-warning">
						[[This button deletes the user's subscription as if the user had never had any subscription. This action would not expire user's listings and would not send expiration notification to the user.]]
					</div>
				</form>
			</div>

			<div class="changeUserGroupForm modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
							<h4 class="modal-title"></h4>
						</div>
						<div class="modal-body">
							{if $userContractId}
								<div class="alert alert-warning hint">
									[[Warning: If you change User Group the user may lose subscription. The system will ask the user to subscribe to a new Membership Plan next time he wants to post a listing.]]
								</div>
							{/if}
							<form class="form form-horizontal">
								<div class="form-group">
									<label class="control-label col-sm-5">[[Current User Group]]:</label>

									<div class="col-sm-6 userGroupValue">[[$userGroupInfo.name]]</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-5">[[Change to]]:</label>

									<div class="col-sm-6">
										<select name="groupId" class="form-control">
											{foreach from=$userGroupOptions item=group}
												{if $group.id != $userGroupInfo.id}
													<option value='{$group.id}'>[[$group.caption]]</option>
												{/if}
											{/foreach}
										</select>
									</div>
								</div>
								<div class="clearfix">
									<input type="hidden" name="users[{$user.sid}]" value="{$user.sid}">
									<input type="submit" value="[[Submit:raw]]" class="btn btn-default btn-block btn-xs">
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			{include file="letter_form.tpl"}
		</div>
	</div>

	{include file="miscellaneous^dialog_window.tpl"}
	{require component="jquery" file="jquery.js"}
	{require component="jquery-ui" file="jquery-ui.js"}
	{require component="jquery-ui" file="css/smoothness/jquery-ui.css"}
	<script type="text/javascript">
		function displayPopupForm(popupFormSelector, anchor, formTitle) {
			$('form', popupFormSelector).submit(function () {
				window.location.href = $(anchor).attr("href") + "&" + $(this).serialize();
				return false;
			});

			$('.modal-title', popupFormSelector).text(formTitle);
			$(popupFormSelector).modal();

			return false;
		}

		$(document).ready(function () {

			$("a.action.popup").click(function () {
				openDialogWindow($(this).text(), this.href + '?users[]={$user.sid}&returnBackUri=/edit_user/?username={$user.username}', 400, true, this.rel);
				return false;
			});

			$("form#deleteContactForm").submit(function () {
				return confirm("[[Do you really want to delete user`s subscription?:raw]]");
			});
		});

	</script>
</div>
