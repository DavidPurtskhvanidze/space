{if !empty($warning)}
    <p class="alert bg-info text-center" role="alert">{$warning}</p>
{/if}

{if !empty($errors)}
    <div class="alert alert-danger text-center" role="alert">
        {include file="errors.tpl" errors=$errors}
    </div>
{/if}

<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="login-window login-page text-center">
            <form role="form" action="{page_path id='user_login'}" method="post">
                {CSRF_token}
                <input type="hidden" name="action" value="login" />
                <input type="hidden" name="HTTP_REFERER" value="{$HTTP_REFERER}" />
                <input type="hidden" name="QUERY_STRING" value="{$QUERY_STRING}" />

                <div class="form-group">
                    <div class="h4">[[login]]</div>
                </div>

                <div class="form-group">
                    <label class="sr-only" for="Username">[[Username]]</label>
                    <input name="username" type="text" class="form-control" id="Username" placeholder="[[Username:raw]]">
                </div>
                <div class="form-group">
                    <label class="sr-only" for="Password">[[Password]]</label>
                    <input name="password" type="password" class="form-control" id="Password" placeholder="[[Password:raw]]">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-orange h4">[[Login]]</button>
                </div>

                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="keep" /> [[Keep me signed in]]
                    </label>
                </div>
                <a class="forgot-password-link btn btn-link" href="{page_path id='password_recovery'}">[[Forgot Your Password?]]</a>
            </form>
            {module name="third_party_login" function="display_form" queryString=$QUERY_STRING httpReferer=$HTTP_REFERER}
        </div>
    </div>

</div>

