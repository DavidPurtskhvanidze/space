<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="loginPage thumbnail">
	        {if !empty($warning)}
                <p class="alert alert-warning" role="alert">{$warning}</p>
            {/if}

            {if !empty($errors)}
                <div class="alert alert-danger" role="alert">
                    {include file="errors.tpl" errors=$errors}
                </div>
            {/if}
            <form role="form" action="{page_path id='user_login'}" method="post">
                {CSRF_token}
                <input type="hidden" name="action" value="login" />
                <input type="hidden" name="HTTP_REFERER" value="{$HTTP_REFERER}" />
                <input type="hidden" name="QUERY_STRING" value="{$QUERY_STRING}" />

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="sr-only" for="Username">[[Username]]</label>
                            <input name="username" type="text" class="form-control" id="Username" placeholder="[[Username:raw]]">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="sr-only" for="Password">[[Password]]</label>
                            <input name="password" type="password" class="form-control" id="Password" placeholder="[[Password:raw]]">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary form-control">[[Login]]</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="keep" /> [[Keep me signed in]]
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <a class="forgot-password-link btn btn-link" href="{page_path id='password_recovery'}">[[Forgot Your Password?]]</a>
                    </div>
                    <div class="col-md-4">&nbsp;</div>
                </div>
            </form>
            {module name="third_party_login" function="display_form" queryString=$QUERY_STRING httpReferer=$HTTP_REFERER}
        </div>
    </div>

</div>

