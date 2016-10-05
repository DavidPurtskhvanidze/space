<div class="reportImproperContentPage">
<h1>[[Report Inappropriate Content]]</h1>
{if $message_sent}
<p class="success">
	<span>[[Your message was sent.]]</span>
</p>
{elseif !empty($errors)}
{include file="errors.tpl"}
{else}
<div class="description">
	[[If you find the content of the {$objectType} $objectId inappropriate, and believe it should be removed from our website, please let us know that by filling out the form below. Your inputs will be sent to the administrator for review and action.]]
</div>

{display_error_messages}
<p>[[Fields marked with (<span class="asterisk">*</span>) are mandatory]]</p>
<form action="" method="post">
	<table class="form reportImporterForm">
		<tr>
			<td>[[$form_fields.FullName.caption]]</td>
			<td>{if $form_fields.FullName.is_required}<span class="asterisk">*</span>{else}&nbsp;{/if}</td>
			<td>{input property="FullName"}</td>
		</tr>
		<tr>
			<td>[[$form_fields.Email.caption]]</td>
			<td>{if $form_fields.Email.is_required}<span class="asterisk">*</span>{else}&nbsp;{/if}</td>
			<td>{input property="Email"}</td>
		</tr>
		<tr>
			<td>[[$form_fields.Report.caption]]</td>
			<td>{if $form_fields.Report.is_required}<span class="asterisk">*</span>{else}&nbsp;{/if}</td>
			<td>{input property="Report"}</td>
		</tr>
		{if isset($form_fields.captcha)}
		<tr>
			<td>[[$form_fields.captcha.caption]]</td>
			<td>{if $form_fields.captcha.is_required}<span class="asterisk">*</span>{else}&nbsp;{/if}</td>
			<td>{input property="captcha"}</td>
		</tr>
		{/if}
		<tr>
			<td colspan="2">
				<input type="hidden" name="action" value="report" />
				<input type="hidden" name="objectType" value="{$objectType}" />
				<input type="hidden" name="objectId" value="{$objectId}" />
				<input type="hidden" name="returnBackUri" value="{$returnBackUri}" />
				<input type="submit" value="[[Send:raw]]" class="contactButton" />
			</td>
		</tr>
	</table>
</form>
{/if}

<br />
{assign var='backToListingLink' value=$GLOBALS.site_url|cat:$returnBackUri}
{if $message_sent or !empty($errors)}
    [[Click <a href="$backToListingLink">here</a> to go back to listing.]]
{/if}
	<script type="text/javascript" src="{url file="field_types^showInputError.js"}"></script>
</div>
