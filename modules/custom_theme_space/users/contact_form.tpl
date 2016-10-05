<div class="contactFormPage contactSellerFormPage">
	{if $message_sent}
		<script type="text/javascript">
			location.href = location.href.split('#')[0];
		</script>
	{elseif !empty($errors)}
	{include file="errors.tpl"}
	{else}
	{display_error_messages}
	<div class="alert alert-warning text-center">[[Fields marked with an asterisk (<span class="asterisk">*</span>) are mandatory]]</div>
	<form action="" method="post">
		<div class="form-group">
            <div class="input-group">
                {input property="FullName" placeholder="{$form_fields.FullName.caption}"}
                <span class="input-group-addon">{if $form_fields.FullName.is_required}<span class="asterisk">*</span>{/if}</span>
            </div>
		</div>

        <div class="form-group">
            <div class="input-group">
                {input property="Email" placeholder="{$form_fields.Email.caption}"}
                <span class="input-group-addon">{if $form_fields.Email.is_required}<span class="asterisk">*</span>{/if}</span>
            </div>
		</div>

		<div class="form-group">
            <div class="input-group">
                {input property="Request"  parameters=["height"=>"200px"] placeholder="{$form_fields.Request.caption}"}
                <span class="input-group-addon">{if $form_fields.Request.is_required}<span class="asterisk">*</span>{/if}</span>
            </div>
		</div>
		{if isset($form_fields.captcha)}
		<div class="form-group">
            <div class="input-group">
                {input property="captcha" placeholder="{$form_fields.captcha.caption}"}
                <span class="input-group-addon">{if $form_fields.captcha.is_required}<br/><br/><br/><span class="asterisk">*</span>{/if}</span>
            </div>
		</div>
		{/if}
		<div class="form-group">
            <input type="hidden" name="action" value="send_message" />
            <input type="hidden" name="listing_sid" value="{$listing_sid}">
            <input type="hidden" name="user_sid" value="{$user_sid}">
            <div class="input-group">
                <input type="submit" value="[[Send:raw]]" class="default-button form-control wb" />
            </div>
		</div>
	</form>
	{/if}

    <br />
    {if !empty($errors)}
        <p>* [[You can try to send this message again]]:
            <form action="" method="post">
                {CSRF_token}
                <input type="hidden" name="action" value="resend_message" />
                <input type="hidden" name="serialized_data" value="{$serialized_data}" />
				<input type="submit" value="[[Resend:raw]]" class="button btn btn-default" />
            </form>
        </p>
        {if $listing_sid}
            <p><a href="{page_path id='listing'}{$listing_sid}/">[[Back to the Listing]]</a></p>
            <p><a href="{page_path id='comments'}{$listing_sid}/">[[View all comments]]</a></p>
        {/if}
        <p><a href="{page_path id='users_contact'}{$user_sid}/{if $listing_sid}?listing_sid={$listing_sid}{/if}">[[Send another message]]</a></p>
    {/if}
</div>
