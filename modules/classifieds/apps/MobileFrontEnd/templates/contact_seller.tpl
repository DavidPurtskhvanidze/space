<div class="contactSellerPage">
<h1>[[Contact Seller]]</h1>
{if $message_sent}
	<p class="success">
		{strip}
			<span>[[Your message has been successfully sent to the seller]].</span>
			{if $REQUEST.returnBackUrl}
				{assign var="returnBackUri" value=$GLOBALS.site_url|cat:$REQUEST.returnBackUrl}
				<span>[[You can return back <a href="$returnBackUri">to the listing</a>]]</span>
				{if $REQUEST.searchResultsUrl}
					{assign var="searchResultsUrl" value=$GLOBALS.site_url|cat:$REQUEST.searchResultsUrl}
					<span> [[or <a href="$searchResultsUrl">to the search results</a>]]</span>
				{/if}.
			{/if}
		{/strip}
	</p>
{elseif !empty($ERRORS)}
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
			<input type="hidden" name="listing_id" value="{$REQUEST.listing_id}" />
			<input type="hidden" name="returnBackUrl" value="{$REQUEST.returnBackUrl}" />
			<input type="hidden" name="searchResultsUrl" value="{$REQUEST.searchResultsUrl}" />
			<input type="submit" value="[[Send:raw]]" />
		</fieldset>
	</form>
{/if}
</div>
