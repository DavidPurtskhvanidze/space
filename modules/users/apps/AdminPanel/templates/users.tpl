<div class="page-content">
{if $search.total_found > 0}
	{include file="errors.tpl"}
	{include file="messages.tpl"}
	{display_success_messages}
	{display_error_messages}
	<div class="row">
		<div class="col-xs-12 usersBlock">
			<div class="table-responsive">
				<div class="dataTables_wrapper" role="grid">
					<div class="row">
						<div class="col-sm-4">
							{include file="miscellaneous^items_per_page_selector.tpl" search=$search}
						</div>
						<div class="col-sm-8 text-right searchResultControls">

							<div class="btn-group text-left">
								{if $REQUEST.hidePictures == 1}
									<a class="btn btn-xs btn-primary" href="?action=restore&amp;hidePictures=0">[[Display pictures]]</a>
								{else}
									<a class="btn btn-xs btn-primary" href="?action=restore&amp;hidePictures=1">[[Hide pictures]]</a>
								{/if}
							</div>
							<div class="btn-group text-left">
								{$url = "?action=restore&amp;searchId={$search.id}"}
								{include file="miscellaneous^sorting_field_selector.tpl" url=$url search=$search sortingFields=$sortingFields}
							</div>
							<div class="btn-group text-left">
								<a href="#" class="btn btn-primary dropdown-toggle btn-xs actionWithSelected" data-toggle="dropdown">
									[[Actions with selected]]
									<i class="icon-angle-down icon-on-right"></i>
								</a>
								<ul class="dropdown-menu dropdown-info pull-right actionList">
									<li><a onclick="return submitItemSelectorForm(this, false)" href="?action=Activate">[[Activate]]</a></li>
									<li><a onclick="return submitItemSelectorForm(this, false)" href="?action=Deactivate">[[Deactivate]]</a></li>
									<li><a onclick="return submitItemSelectorForm(this, false)" href="?action=Send+Activation+Letter">[[Send Activation Letter]]</a></li>
									<li><a onclick="return displayPopupForm('#sendLetterForm', this);" href="?action=Send+Letter">[[Send Letter]]</a></li>
									<li><a onclick="return submitItemSelectorForm(this, '[[Are you sure that you want to delete selected users?:raw]]')" href="?action=Delete">[[Delete]]</a></li>
									<li><a onclick="return submitItemSelectorForm(this, false)" href="?action=Make+Trusted">[[Make Trusted]]</a></li>
									<li><a onclick="return submitItemSelectorForm(this, false)" href="?action=Make+Untrusted">[[Make Untrusted]]</a></li>
									<li><a onclick="return displayPopupForm('.changeUserGroupForm', this);" href="?action=CHANGE+USER+GROUP" class="action changeUserGroup">[[Change User Group]]</a></li>
									{foreach from=$userMassActions item='action'}
										<li><a href="{$GLOBALS.site_url}{$action->getUri()}" class="action popup">[[{$action->getCaption()}]]</a></li>
									{/foreach}
								</ul>
							</div>
						</div>
					</div>

					<form method="post" id="manage_users_form" name="itemSelectorForm">
                        {CSRF_token}
						<table class="table table-striped table-hover dataTable">
							<thead>
							<tr role="row">
								<th class="center">
									<label>
										<input class="ace check-all" type="checkbox"/>
										<span class="lbl"></span>
									</label>
								</th>
								{if !$REQUEST.hidePictures}
									<th></th>
								{/if}
								<th></th>
								<th></th>
								<th></th>
							</tr>
							</thead>
							<tbody>
							{foreach from=$users item=user name=users_block}
								{assign var="user_sid" value=$user.sid}
								<tr class="searchResultItem">
									<td class="align-middle center">
										<label>
											<input class="ace" type="checkbox" name="users[{$user_sid}]" value="{$user_sid}" id="checkbox_{$smarty.foreach.users_block.iteration}"{if in_array($user_sid, $checkedUsers)} checked="checked"{/if} />
											<span class="lbl"></span>
										</label>
									</td>
									{if !$REQUEST.hidePictures}
										<td class="align-middle">
											<div class="profile-photo img-thumbnail">
												{if $user.ProfilePicture.exists && $user.ProfilePicture.ProfilePicture.name}
													<a href="{page_path id='edit_user'}?username={$user.username}">
														<img src="{$user.ProfilePicture.ProfilePicture.url}"/>
													</a>
												{else}
													<a href="{page_path id='edit_user'}?username={$user.username}">
														<img src="{url file='main^user_big.png'}" alt="[[No photos:raw]]"/>
													</a>
												{/if}
											</div>
										</td>
									{/if}
									<td>
										<ul class="list-unstyled">
											<li><h2><a href="{page_path id='edit_user'}?username={$user.username}">{$user.username}</a></h2></li>
											<li><i class="icon-envelope blue"></i> {$user.email}</li>
											<li><i class="icon-user blue"></i> [[{$user.group.name}]]</li>
										</ul>
									</td>
									<td>
										<ul class="list-unstyled">
											<li>
												<i class="icon-angle-right bigger-110"></i>
												<a class="itemControls numberOfListings" href="{$GLOBALS.site_url}/manage_listings/?action=search&amp;username[like]={$user.username}">{$user.numberOfListings} [[Listings]]</a>
											</li>
											<li>
												<i class="icon-angle-right bigger-110"></i>
												{if $user.active.isTrue}<span class="fieldValue fieldValueActive active">[[Active]]</span>{else}<span class="fieldValue fieldValueActive inactive">[[Inactive]]</span>{/if}
											</li>
											<li>
												<i class="icon-angle-right bigger-110"></i>
												{if $user.trusted_user.isTrue}<span class="fieldValue fieldValueTrustedUser trusted">[[Trusted]]</span>{else}<span class="fieldValue fieldValueTrustedUser untrusted">[[Untrusted]]</span>{/if}
											</li>
											{module name='users' function='display_user_metadata' user_sid=$user_sid}
											<li><i class="icon-angle-right bigger-110"></i> [[Registered on]] {tr type="date"}{$user.registration_date}{/tr}  {$user.registration_date|date_format:"%H:%M:%S"}</li>
										</ul>
									</td>
									<td>
										<div class="btn-group actionList">

											{if $user.active.isTrue}
												<a class="itemControls deactivate btn btn-xs btn-warning" href="{page_path id='users'}?action=deactivate&users[{$user_sid}]={$user_sid}" title="[[Deactivate]]">
													<i class="icon-ban-circle bigger-120"></i>
												</a>
											{else}
												<a class="itemControls activate btn btn-xs btn-primary" href="{page_path id='users'}?action=activate&users[{$user_sid}]={$user_sid}" title="[[Activate]]">
													<i class="icon-check-sign bigger-120"></i>
												</a>
											{/if}

											<a class="itemControls send btn btn-xs btn-success" href="?action=Send+Activation+Letter&users[{$user_sid}]={$user_sid}" title="[[Send Activation Letter]]">
												<i class="icon-envelope bigger-120"></i>
											</a>
											<a class="itemControls edit btn btn-xs btn-info" href="{page_path id='edit_user'}?username={$user.username}" title="[[Edit:raw]]">
												<i class="icon-edit bigger-120"></i>
											</a>
											<a class="itemControls delete btn btn-xs btn-danger" href="{page_path id='users'}?action=delete&users[{$user_sid}]={$user_sid}"
											   onclick='return confirm("[[Are you sure that you want to delete this user?:raw]]")' title="[[Delete:raw]]">
												<i class="icon-trash bigger-120"></i>
											</a>
										</div>
									</td>
								</tr>
							{/foreach}
							</tbody>
						</table>
					</form>
					<div class="row">
						<div class="col-sm-6"></div>
						<div class="col-sm-6">
							{include file="miscellaneous^page_selector.tpl" search=$search}
						</div>
					</div>
				</div>
				<!-- dataTables_wrapper -->
			</div>
		</div>
	</div>
	<div class="changeUserGroupForm modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title">[[Change User Group]]</h4>
				</div>
				<div class="modal-body">
					<div class="alert alert-warning hint" role="alert">
						[[Warning: If you change User Group selected users may lose their subscription. The system will ask them to subscribe to a new Membership Plan next time they post a listing.]]
					</div>
					<form>
						<table class="properties table">
							<tr>
								<td>[[Change to]]</td>
								<td>
									<select name="groupId" class="form-control">
										{foreach from=$userGroupOptions item=group}
											<option value='{$group.id}'>[[$group.caption]]</option>
										{/foreach}
									</select>
								</td>
							</tr>
							<tr>
								<td></td>
								<td><input type="submit" value="[[Submit:raw]]" class="btn btn-default"></td>
							</tr>
						</table>
					</form>
				</div>
			</div>
		</div>
	</div>
	{include file="letter_form.tpl"}
	{include file="miscellaneous^dialog_window.tpl"}
	{include file="miscellaneous^search_result_item_actions_js.tpl"}
	{require component="jquery" file="jquery.js"}
	{require component="jquery-ui" file="jquery-ui.js"}
	{require component="jquery-ui" file="css/smoothness/jquery-ui.css"}
	<script type="text/javascript">
		var noSelectedItemsMessage = "[[You have not selected any items. Please select one or more items and proceed with actions.:raw]]";

		function displayPopupForm(popupFormSelector, anchor) {
			$('form', popupFormSelector).submit(function () {
				window.location.href =
						$(anchor).attr("href") + "&" +
						$(this).serialize() + '&' +
						$("form[name='itemSelectorForm']").serialize();
				return false;
			});
			$(popupFormSelector).modal('show');
			return false;
		}

		function submitItemSelectorForm(anchor, confirmationMessage) {

			if (confirmationMessage && !confirm(confirmationMessage)) {
				return false;
			}
			window.location.href =
					$(anchor).attr("href") + "&" +
					$("form[name='itemSelectorForm']").serialize();
			return false;
		}

		$(document).ready(function () {
			$(".changeUserGroupForm").modal({
				show: false
			});

			$(".actionWithSelected").click(function () {
				if (!$('input[name^=users]:checked').length) {
					$(this).addClass("disabled");
					alert(noSelectedItemsMessage);
				}
				else
					$(this).removeClass("disabled");
			});

			$('.table tr input:checkbox').on('change', function () {
				if ($(this).prop("checked")) {
					$(".actionWithSelected").removeClass("disabled");
				}
			});

			$('table th input:checkbox').on('click', function () {
				var that = this;
				$(this).closest('table').find('tr > td:first-child input:checkbox')
						.each(function () {
							this.checked = that.checked;
							$(this).closest('tr').toggleClass('selected');
						});
			});

			$("a.action.popup").click(function () {
				openDialogWindow($(this).text(), this.href + "?" + $("form[name='itemSelectorForm']").serialize(), 400, true);
				return false;
			});
		});
	</script>
	{include file="miscellaneous^multilevelmenu_js.tpl"}
{else}
	<p class="error">[[There are no users available that match your search criteria. Please try to broaden your search criteria.]]</p>
{/if}
</div>
