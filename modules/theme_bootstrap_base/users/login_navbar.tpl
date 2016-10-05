<form class="navbar-form login-form" role="form" action="{page_path id='user_login'}" method="post">
    {CSRF_token}
    <input type="hidden" name="action" value="login" />
    <input type="hidden" name="HTTP_REFERER" value="{$HTTP_REFERER}" />
    <input type="hidden" name="QUERY_STRING" value="{$QUERY_STRING}" />
	<div class="form-group">
		<input name="username" type="text" class="form-control" placeholder="[[Username or Email:raw]]">
	</div>
	<div class="form-group">
		<input name="password" type="password" class="form-control" placeholder="[[Password:raw]]">
	</div>

    <div class="row">
        <div class="col-md-8">
            <div class="checkbox small">
                <label>
                    <input class="middle"  type="checkbox" name="keep" /> <span class="middle">[[Keep me signed in]]</span>
                </label>
            </div>
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn form-control pull-right form-control  btn-primary">[[Login]]</button>
        </div>
    </div>


    <div class="passwordRecovery small">
        <a href="{page_path id='password_recovery'}">[[Forgot Your Password?]]</a><br/>
    </div>


</form>
{module name="third_party_login" function="display_form" queryString=$QUERY_STRING httpReferer=$HTTP_REFERER}
