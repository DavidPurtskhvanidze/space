<div class="userNotifications">
	<h1 class="page-title">[[My Notifications]]</h1>
    <div class="space-20"></div>
	{display_success_messages}
	{display_error_messages}
    <div class="space-20"></div>
	{if $showForm}
		{if $noUserEmail}
			{capture assign="profilePageLink"}{page_path id='user_profile'}{/capture}
			<div class="hint">[[Your Profile is missing the email address. Notifications require your valid email address in order to work properly. Please <a href="$profilePageLink">update your Profile information</a> by adding your email address.]]</div>
		{/if}
		<form method="post" action="">
            {CSRF_token}
			<div class="myNotifications">
                <div class="row">
                    {foreach from=$notificationSettings item=notificationSetting}
                        <div class="col-sm-6 col-xs-12">
                            <div class="custom-form-control">
                                <input type="checkbox" id="{$notificationSetting.name}" name="{$notificationSetting.name}" value="1"{if $notificationSetting.value} checked="checked"{/if} {if $noUserEmail}disabled{/if} />
                                <label class="checkbox" for="{$notificationSetting.name}">[[$notificationSetting.caption]]</label>
                            </div>
                        </div>
                    {/foreach}
                </div>
			</div>
            <div class="space-20"></div>
            <div class="space-20"></div>
            <div class="space-20"></div>
            <input type="hidden" name="action" value="save"/>
            <div class="text-center">
                <button type="submit" class="btn btn-orange h5" value="[[Save:raw]]" {if $noUserEmail}disabled{/if}>[[Save:raw]]</button>
            </div>
            <div class="space-20"></div>
            <div class="space-20"></div>
            <div class="space-20"></div>
		</form>
	{/if}
</div>
