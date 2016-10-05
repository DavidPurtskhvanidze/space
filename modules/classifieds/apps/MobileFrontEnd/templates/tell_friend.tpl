<div class="tellFriendPage">
    <h1><span class="tellFriendHeader">[[Recommend listing #]]{$REQUEST.listing_id}&nbsp;</span><span
            class="tellFriendHeader">{$REQUEST.listing_title}</span></h1>
    <div class="clearBoth"></div>
{if $message_sent}
	<p class="success">
		{strip}
			{assign var="friendEmail" value=$REQUEST.friend_email}
			<span>[[Your message has been successfully sent to the email $friendEmail]].</span><br />
			<span>[[You can send a message to another friend filling the form below]]</span>
			{if $REQUEST.returnBackUrl}
				{assign var="returnBackUri" value=$GLOBALS.site_url|cat:$REQUEST.returnBackUrl}
				<span> [[OR <a href="$returnBackUri">return back to the listing</a>]]</span>
			{/if}.
		{/strip}
	</p>
{elseif !empty($ERRORS)}
	{include file="errors.tpl"}
{/if}
	{display_error_messages}

	<p class="mandatoryMarkNotification">[[Fields marked with (<span class="asterisk">*</span>) are mandatory]]</p>
	<form action="" method="post">
		<fieldset>
			<div class="formField name">
				<label for="name">[[$form_fields.name.caption]] {if $form_fields.name.is_required}<span class="asterisk">*</span>{/if}</label>
				{input property="name"}
			</div>
			<div class="formField friend_name">
				<label for="friend_name">[[$form_fields.friend_name.caption]] {if $form_fields.friend_name.is_required}<span class="asterisk">*</span>{/if}</label>
				{input property="friend_name"}
			</div>
			<div class="formField friend_email">
				<label for="friend_email">[[$form_fields.friend_email.caption]] {if $form_fields.friend_email.is_required}<span class="asterisk">*</span>{/if}</label>
				{input property="friend_email"}
			</div>
			<div class="formField comment">
				<label for="comment">[[$form_fields.comment.caption]] {if $form_fields.comment.is_required}<span class="asterisk">*</span>{/if}</label>
				{input property="comment"}
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
			<input type="hidden" name="listing_id" value="{$REQUEST.listing_id}" />
			<input type="hidden" name="returnBackUrl" value="{$REQUEST.returnBackUrl}" />
			<input type="submit" value="[[Send:raw]]" />
		</fieldset>
	</form>
</div>
