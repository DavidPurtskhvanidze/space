<div class="form-group">
    <label class="col-sm-3 control-label">
      [[Default User Group]]
    </label>
    <div class="col-sm-8">
        <select name="third_party_auth_user_group_sid" class="form-control">
            <option value="">[[Select Default User Group:raw]]</option>
            {foreach from=$allUserGroupsInfo item=userGroupInfo}
                <option value="{$userGroupInfo.sid}"{if $settings.third_party_auth_user_group_sid == $userGroupInfo.sid} selected{/if}>[[$userGroupInfo.name]]</option>
            {/foreach}
        </select>
        <div class="help-block">
            [[Please refer to User Manual -> Facebook, Twitter and OpenID Login]]
        </div>
    </div>
</div>
