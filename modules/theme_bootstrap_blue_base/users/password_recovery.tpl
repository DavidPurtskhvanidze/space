<div class="passwordRecoveryPage">
	<h1 class="page-title">[[Password Recovery]]</h1>
	{display_error_messages}
	<p class="alert bg-info text-center">
		[[Please, enter your username in the field below and we'll send you a link to a page where you can change your password]]:
	</p>
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3">
            <form class="form-horizontal passwordRecoveryForm" role="form" method="post">
                {CSRF_token}
                <div class="form-group">
                    <input type="text" name="username" value="{$username}" class="form-control" />
                </div>
                <div class="form-group text-center">
                    <button type="submit" class="btn btn-orange btn1">[[Submit:raw]]</button>
                </div>
            </form>
        </div>
    </div>
</div>
