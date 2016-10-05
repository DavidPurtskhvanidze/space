<div class="contactFormPage">
	{if $message_sent == false}
		{i18n->getCurrentLanguage assign="currentLanguage"}
		{module name="static_content" function="show_static_content" pageid='Contact_'|cat:$currentLanguage}

		{display_error_messages}
		<div class="contactForm">
			<form method="post" action="">
				<input type="hidden" name="action" value="send_message"/>
				<input type="hidden" name="returnBackUri" value="{$returnBackUri}"/>

				<div class="contactFormName">
					<b>[[Salutation, First and Last Name]]</b> {if $form_fields.name.is_required}<span class="asterisk">*</span>{/if}
					<br/>
					{input property="name"}
					<br/>
					<small>Mr. John Smith</small>
				</div>

				<div class="contactFormEmail">
					<b>[[Email]]</b> {if $form_fields.email.is_required}<span class="asterisk">*</span>{/if}<br/>
					{input roperty="email"}
					<br/>
					<small>johnsmith@company.com</small>
				</div>

				<div class="contactFormComments">
					<b>[[Comments]]</b> {if $form_fields.comments.is_required}<span class="asterisk">*</span>{/if}<br/>
					{input property="comments"}
				</div>
				{if isset($form_fields.captcha)}
					<div class="contactFormCaptcha">
						[[Enter a code from the image below]] <span class="asterisk">*</span><br/>
						{input property="captcha"}
					</div>
				{/if}
				<input type="submit" value="[[Submit:raw]]"/>
			</form>
		</div>
		<div class="contactDetailsWrapper">
			<div class="contactDetails">
				<h3>[[Contact Details]]</h3>
				<hr class="soften">
				{if !$GLOBALS.Dealer.ProfilePicture.isEmpty}
					<img src="{$GLOBALS.Dealer.ProfilePicture.file_url}" alt="[[Profile Picture:raw]]"
					     class="img-responsive"/>
				{else}
					<img src="{url file='main^user_big.png'}" alt="[[No photos:raw]]" class="img-responsive"/>
				{/if}
				<address>
					<h4>&nbsp;{$GLOBALS.Dealer.FirstName} {$GLOBALS.Dealer.LastName}</h4>

					<span class="telephone">{$GLOBALS.Dealer.PhoneNumber}<span></span></span>

					<a class="email" href="mailto:{$GLOBALS.Dealer.email}"
					   title="[[Contact us by email]]">{$GLOBALS.Dealer.email}<span></span></a>
				</address>
				<hr class="soften">
			</div>
		</div>
	{else}
		<p>[[Thank you very much for your message. We will respond to you as soon as possible.]]</p>
		{if $returnBackUri}
			{assign var='backToListingLink' value=$GLOBALS.site_url|cat:$returnBackUri}
			[[Click
			<a href="$backToListingLink">here</a>
			to go back to listing.]]
		{/if}
	{/if}
</div>
