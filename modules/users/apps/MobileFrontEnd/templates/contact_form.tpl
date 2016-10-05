<div class="contactUserFormPage">
	<h1>[[Contact Form]]</h1>
	{if $message_sent}
		<p>[[Your message was sent.]]</p>
	{elseif !empty($errors)}
		{include file="errors.tpl"}
	{else}
		{display_error_messages}

		<p class="mandatoryMarkNotification">[[Fields marked with (<span class="asterisk">*</span>) are mandatory]]</p>
		<form action="" method="post">
			<fieldset>
				<div class="formField FullName">
					<label for="FullName">[[$form_fields.FullName.caption]] {if $form_fields.FullName.is_required}<span class="asterisk">*</span>{/if}</label>
					{input property="FullName"}
				</div>
				<div class="formField Email">
					<label for="Email">[[$form_fields.Email.caption]] {if $form_fields.Email.is_required}<span class="asterisk">*</span>{/if}</label>
					{input property="Email"}
				</div>
				<div class="formField Request">
					<label for="Request">[[$form_fields.Request.caption]] {if $form_fields.Request.is_required}<span class="asterisk">*</span>{/if}</label>
					{input property="Request"}
				</div>
				{if isset($form_fields.captcha)}
					<div class="formField security_code">
						<label for="security_code">[[$form_fields.captcha.caption]] {if $form_fields.captcha.is_required}<span class="asterisk">*</span>{/if}</label>
						{input property="captcha"}
					</div>
				{/if}
			</fieldset>
			<fieldset class="formControls">
				<input type="hidden" name="action" value="send_message" />
				<input type="hidden" name="listing_sid" value="{$listing_sid}">
				<input type="submit" value="[[Send:raw]]" class="button" />
			</fieldset>
		</form>
	{/if}
    {if !empty($errors)}
        <div class="resendControls">
			<span>* [[You can try to send this message again]]:</span>
            <form action="" method="post">
                {CSRF_token}
                <input type="hidden" name="action" value="resend_message" />
                <input type="hidden" name="serialized_data" value="{$serialized_data}" />
				<input type="submit" value="[[Resend:raw]]" class="button" />
            </form>
        </div>
    {/if}
    {if $message_sent or !empty($errors)}
        {if $listing_sid}
            <p><a href="{page_path id='listing'}{$listing_sid}/">[[Back to the Listing]]</a></p>
            <p><a href="{page_path id='comments'}{$listing_sid}/">[[View all comments]]</a></p>
        {/if}
        <p><a href="{page_path id='users_contact'}{$user_sid}/{if $listing_sid}?listing_sid={$listing_sid}{/if}">[[Send another message]]</a></p>
    {/if}
</div>
