<div class="passwordRecoveryPage">
	<h1>[[Password Recovery]]</h1>
	{display_error_messages}
	<p>
		[[Please, enter your username in the field below and we'll send you a link to a page where you can change your password]]:
	</p>
	<form class="form-inline passwordRecoveryForm" role="form" method="post">
        {CSRF_token}
		<div class="form-group">
			<input type="text" name="username" value="{$username}" class="form-control" />
		</div>
		<button type="submit" class="btn btn-default">[[Submit:raw]]</button>
	</form>
</div>
