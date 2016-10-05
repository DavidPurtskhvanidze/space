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
	{if $actionComplete}
		<script type="text/javascript">
			this.location.href = "{$GLOBALS.site_url}";
		</script>
	{else}
		<div class="recoverForm">
			{display_success_messages}
			<div class="alert alert-info">
					[[Please enter the username of administrator in the field below and we'll send you an email with a link to the page where you can change your password:]]
			</div>
			{display_error_messages}
			<form method="post" action="" class="form form-horizontal">
				<div class="form-group">
					<div class="control-label col-sm-3">[[Admin username]]:</div>
					<div class="col-sm-8 col-sm-offset-0"><input id="admin_username" type="text" name="admin_username" value="{$REQUEST.admin_username}" class="form-control"></div>
				</div>
				<div class="clearfix form-actions">
                    {CSRF_token}
					<input type="hidden" name="action" value="recover_password" />
					<input type="submit" value="[[Submit:raw]]" class="btn btn-default">
				</div>
			</form>
		</div>
    {/if}
</body>
</html>
