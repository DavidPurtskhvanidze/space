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
					<b>[[Salutation, First and Last Name]]</b> {if $form_fields.name.is_required}<span class="asterisk">*</span>{/if}<br />
					{capture assign="defaultName"}{if $GLOBALS.current_user.logged_in}{$GLOBALS.current_user.FirstName} {$GLOBALS.current_user.LastName}{/if}{/capture}
					{input property="name" default=$defaultName}
					<br />
					<small>Mr. John Smith</small>
				</div>

				<div class="contactFormEmail">
					<b>[[Email]]</b> {if $form_fields.email.is_required}<span class="asterisk">*</span>{/if}<br />
					{capture assign="defaultEmail"}{if $GLOBALS.current_user.logged_in}{$GLOBALS.current_user.email}{/if}{/capture}
					{input property="email" default=$defaultEmail}
					<br />
					<small>johnsmith@company.com</small>
				</div>

				<div class="contactFormComments">
					<b>[[Comments]]</b> {if $form_fields.comments.is_required}<span class="asterisk">*</span>{/if}<br />
					{input property="comments"}
				</div>
				{if isset($form_fields.captcha)}
					<div class="contactFormCaptcha">
						[[Enter a code from the image below]] <span class="asterisk">*</span><br />
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
				<address>
					<span class="telephone">[[0123 456 7890]]<span></span></span>

					<a class="email" href="mailto:{$systemEmail}" title="[[Contact us by email]]">[[$systemEmail]]<span></span></a>
				</address>
				<hr class="soften">
				<table class="openingTimes">
					<caption class="secondary">[[Opening Times]]</caption>
					<tbody>
					<tr>
						<td>[[Monday - Friday]]</td>
						<td class="time">[[9.00am - 6.00pm]]</td>
					</tr>
					<tr>
						<td>[[Saturday]]</td>
						<td class="time">[[9.00am - 5.00pm]]</td>
					</tr>
					<tr>
						<td>[[Sunday]]</td>
						<td class="time">[[10.00am - 4.00pm]]</td>
					</tr>
					</tbody>
				</table>
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
