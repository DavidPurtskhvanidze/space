<div class="contactUsFormPage">
<h1>[[Contact Us]]</h1>
{if $message_sent == false}
	{module name="static_content" function="show_static_content" pageid='Contact'}

	{display_error_messages}

	<form method="post">
		<fieldset>
			<div class="formField name">
				<label for="name">[[Salutation, First and Last Name]]</label>
				{capture assign="defaultName"}{if $GLOBALS.current_user.logged_in}{$GLOBALS.current_user.FirstName} {$GLOBALS.current_user.LastName}{/if}{/capture}
				{input property="name" default=$defaultName}
				<small>Mr. John Smith</small>
			</div>
			<div class="formField email">
				<label for="email">[[Email]]</label>
				{capture assign="defaultEmail"}{if $GLOBALS.current_user.logged_in}{$GLOBALS.current_user.email}{/if}{/capture}
				{input property="email" default=$defaultEmail}
				<small>johnsmith@company.com</small>
			</div>
			<div class="formField comments">
				<label for="comments">[[Comments]]</label>
				{input property="comments"}
			</div>
			{if isset($form_fields.captcha)}
				<div class="formField security_code">
					<label for="security_code">[[Enter a code from the image below]]</label>
					{input property="captcha"}
				</div>
			{/if}
		</fieldset>
		<fieldset class="formControls">
			<input type="hidden" name="action" value="send_message">
			<input type="hidden" name="returnBackUri" value="{$returnBackUri}" />
			<input type="submit" value="[[Submit:raw]]">
		</fieldset>
	</form>
{else}
	<p>[[Thank you very much for your message. We will respond to you as soon as possible.]]</p>
	{if $returnBackUri}
		{assign var='backToListingLink' value=$GLOBALS.site_url|cat:$returnBackUri}
		[[Click <a href="$backToListingLink">here</a> to go back to listing.]]
	{/if}
{/if}
</div>
