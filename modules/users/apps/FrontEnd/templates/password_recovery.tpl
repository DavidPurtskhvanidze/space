<div class="passwordRecoveryPage">
	<h1>[[Password Recovery]]</h1>
	{display_error_messages}
	[[Please, enter your username in the field below and we'll send you a link to a page where you can change your password]]:
	<form method="post" action="">
		<div class="passwordRecoveryForm">
            {CSRF_token}
			<input type="text" name="username" value="{$username}" class="text form-control" />
			<input type="submit" name="submit" value="[[Submit:raw]]" class="button" />
		</div>
	</form>
</div>
