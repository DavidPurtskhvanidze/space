<form class="login-form text-center" role="form" action="{page_path id='user_login'}" method="post">
    {CSRF_token}
    <input type="hidden" name="action" value="login" />
    <input type="hidden" name="HTTP_REFERER" value="{$HTTP_REFERER}" />
    <input type="hidden" name="QUERY_STRING" value="{$QUERY_STRING}" />
	<div class="form-group">
        <div class="h4">[[login]]</div>
	</div>
    <div class="form-group">
		<input name="username" type="text" class="form-control" placeholder="[[Username or Email:raw]]">
	</div>
	<div class="form-group">
		<input name="password" type="password" class="form-control" placeholder="[[Password:raw]]">
	</div>
    <div class="form-group">
        <button type="submit" class="btn btn-orange h4">[[Login]]</button>
    </div>
    <div class="form-group">
        <a href="{page_path id='password_recovery'}">[[Forgot Your Password?]]</a>
    </div>

    <div class="form-group keep-me custom-form-control">
        <input id="keep-me" type="checkbox" name="keep" />
        <label class="checkbox" for="keep-me">[[Keep me signed in]]</label>
    </div>
</form>
{module name="third_party_login" function="display_form" queryString=$QUERY_STRING httpReferer=$HTTP_REFERER}
