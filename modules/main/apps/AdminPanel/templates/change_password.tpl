<!DOCTYPE html>
<html lang="{i18n->getCurrentLanguage}">
<head>
	<meta name="keywords" content="{$KEYWORDS}" />
	<meta name="description" content="{$DESCRIPTION}" />
	<title>{$GLOBALS.settings.product_name} Admin Panel {if $TITLE ne ""} :: {$TITLE}{/if}</title>
	<!-- #EXTERNAL_COMPONENTS_PLACEHOLDER# -->

	{includeDesignFiles}
</head>
<body class="{foreach from=$GLOBALS.themeInheritanceBranch item=theme} {$theme->getName()} {/foreach}">
<div class="adminLoginWrapper">
	<div class="changePasswordForm">
		<div class="title">[[Admin Password Recovery Form]]</div>
		{if $displayForm}
			<div class="description">[[Please enter new password and its confirmation below:]]</div>
			<div class="errors">
				{display_error_messages}
			</div>
			<form method="post" action="">
                {CSRF_token}
				<div class="formField formFieldPassword">
					<label for="password">[[FormFieldCaptions!Password]]:</label>
					<input id="password" type="password" name="password" class="form-control">
				</div>
				<div class="formField formFieldConfirmPassword">
					<label for="password">[[FormFieldCaptions!Confirm password]]:</label>
					<input id="confirm_password" type="password" name="confirm_password" class="form-control">
				</div>
				<div class="formControls">
					<input type="hidden" name="action" value="change_password" />
					<input type="hidden" name="username" value="{$username}" />
					<input type="hidden" name="verification_key" value="{$verification_key}" />
					<input type="submit" name="submit" value="[[Submit:raw]]" class="btn btn-default">
				</div>
			</form>
		{else}
			<div class="messages">
				{display_error_messages}
				{display_success_messages}
			</div>
			<a href="{$GLOBALS.site_url}">[[Go back to the Admin Panel login page]]</a>
		{/if}
	</div>
</div>
</body>
</html>
