<div class="changeUserGroupForm">
	<form action="" class="form-horizontal" role="form">
        {CSRF_token}
        {if $userContractId}
            <div class="alert bg-info">
                [[Warning: If you changed User Group you may lose subscription. The system will ask you to subscribe to a new Membership Plan next time you post a listing.]]
            </div>
        {/if}
        <div class="form-group">
            <label class="col-sm-3  control-label">[[Current User Group]]:</label>
            <div class="col-sm-9"><div class="form-control-static">[[$userGroupInfo.name]]</div></div>
        </div>

        <div class="form-group">
            <label class="col-sm-3  control-label">[[Change to]]:</label>
            <div class="col-sm-9">
                <select name="groupId" class="form-control">
                    {foreach from=$userGroupOptions item=group}
                        {if $group.id != $userGroupInfo.id}
                            <option value='{$group.id}'>[[$group.name]]</option>
                        {/if}
                    {/foreach}
                </select>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-3"></div>
            <div class="col-sm-9">
                <input type="hidden" name="action" value="change_user_group" />
                <input type="hidden" name="formIsShownFirstTime" value="1" />
                <input type="submit" value="[[Submit:raw]]" class="btn btn-orange h6"/>
            </div>
        </div>
	</form>
</div>
