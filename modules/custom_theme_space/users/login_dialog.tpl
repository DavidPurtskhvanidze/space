<form class="login-form text-center" role="form" action="{page_path id='user_login'}" method="post">
    {CSRF_token}
    <input type="hidden" name="action" value="login" />
    <input type="hidden" name="HTTP_REFERER" value="{$HTTP_REFERER}" />
    <input type="hidden" name="QUERY_STRING" value="{$QUERY_STRING}" />
    <div class="form-group">
        <img src="{url file='main^img/ilister-prod-logo.png'}" alt="Main Logo">
    </div>
    <div class="form-group">
        <input name="username" type="text" class="form-control" placeholder="[[Username or Email:raw]]">
    </div>
    <div class="form-group">
        <input name="password" type="password" class="form-control" placeholder="[[Password:raw]]">
    </div>
    <div class="form-group">
        <button type="submit" class="login-btn wb">[[Login]]</button>
    </div>
    <div class="form-group">
        <a href="{page_path id='password_recovery'}">[[Forgot Your Password?]]</a>
    </div>

    <div class="form-group keep-me custom-form-control">
        <label>
            [[Keep me signed in]]&nbsp;
            <input id="keep-me" type="checkbox" name="keep" />
            <label for="keep-me"></label>
        </label>

    </div>
</form>
{module name="third_party_login" function="display_form" queryString=$QUERY_STRING httpReferer=$HTTP_REFERER}
