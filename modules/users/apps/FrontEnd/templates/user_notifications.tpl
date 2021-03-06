<div class="userNotifications">
	<h1>[[My Notifications]]</h1>

	{display_success_messages}
	{display_error_messages}

	{if $showForm}
		{if $noUserEmail}
			{capture assign="profilePageLink"}{page_path id='user_profile'}{/capture}
			<div class="hint">[[Your Profile is missing the email address. Notifications require your valid email address in order to work properly. Please <a href="$profilePageLink">update your Profile information</a> by adding your email address.]]</div>
		{/if}
		<form method="post" action="">
			<div class="myNotifications">
				{foreach from=$notificationSettings item=notificationSetting}
					<div>
						<input type="hidden" name="{$notificationSetting.name}" value="0"/>
						<label>
							<input type="checkbox" name="{$notificationSetting.name}" value="1"{if $notificationSetting.value} checked="checked"{/if} {if $noUserEmail}disabled{/if} />
							[[$notificationSetting.caption]]
						</label>
					</div>
				{/foreach}
				<div>
					<input type="hidden" name="action" value="save"/>
                    {CSRF_token}
					<input type="submit" class="button" value="[[Save:raw]]" {if $noUserEmail}disabled{/if} />
				</div>
			</div>
		</form>
	{/if}
</div>
