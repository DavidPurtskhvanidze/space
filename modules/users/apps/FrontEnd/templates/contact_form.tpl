<div class="contactFormPage">
	{if $message_sent}
		<script type="text/javascript">
			location.href = location.href.split('#')[0];
		</script>
	{elseif !empty($errors)}
	{include file="errors.tpl"}
	{else}
	{display_error_messages}
	<p>[[Fields marked with an asterisk (<span class="asterisk">*</span>) are mandatory]]</p>
	<form action="" method="post">
	<table class="form">
		<tr>
			<td class="inputFormCaption inputFormCaptionFullName">
				[[$form_fields.FullName.caption]]
				{if $form_fields.FullName.is_required}<span class="asterisk">*</span>{/if}
			</td>
			<td class="inputFormValue inputFormValueFullName">{input property="FullName"}</td>
		</tr>
		<tr>
			<td class="inputFormCaption inputFormCaptionEmail">
				[[$form_fields.Email.caption]]
				{if $form_fields.Email.is_required}<span class="asterisk">*</span>{/if}
			</td>
			<td class="inputFormValue inputFormValueEmail">{input property="Email"}</td>
		</tr>
		<tr>
			<td class="inputFormCaption inputFormCaptionRequest">
				[[$form_fields.Request.caption]]
				{if $form_fields.Request.is_required}<span class="asterisk">*</span>{/if}
			</td>
			<td class="inputFormValue inputFormValueRequest">{input property="Request"  parameters=["height"=>"200px"]}</td>
		</tr>
		{if isset($form_fields.captcha)}
		<tr>
			<td class="inputFormCaption inputFormCaptionCaptcha">
				[[$form_fields.captcha.caption]]
				{if $form_fields.captcha.is_required}<span class="asterisk">*</span>{/if}
			</td>
			<td class="inputFormValue inputFormValueCaptcha">{input property="captcha"}</td>
		</tr>
		{/if}
		<tr>
			<td>&nbsp;</td>
			<td class="inputFormControls">
				<input type="hidden" name="action" value="send_message" />
                <input type="hidden" name="listing_sid" value="{$listing_sid}">
                <input type="hidden" name="user_sid" value="{$user_sid}">
				<input type="submit" value="[[Send:raw]]" class="button" />
			</td>
		</tr>
	</table>
	</form>
	{/if}

    <br />
    {if !empty($errors)}
        <p>* [[You can try to send this message again]]:
            <form action="" method="post">
                {CSRF_token}
                <input type="hidden" name="action" value="resend_message" />
                <input type="hidden" name="serialized_data" value="{$serialized_data}" />
				<input type="submit" value="[[Resend:raw]]" class="button" />
            </form>
        </p>
        {if $listing_sid}
            <p><a href="{page_path id='listing'}{$listing_sid}/">[[Back to the Listing]]</a></p>
            <p><a href="{page_path id='comments'}{$listing_sid}/">[[View all comments]]</a></p>
        {/if}
        <p><a href="{page_path id='users_contact'}{$user_sid}/{if $listing_sid}?listing_sid={$listing_sid}{/if}">[[Send another message]]</a></p>
    {/if}
</div>
