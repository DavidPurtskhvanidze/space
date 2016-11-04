<h3 class="title">
    [[Login]]
</h3>
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        {if !empty($warning)}
            <p class="alert alert-warning" role="alert">{$warning}</p>
        {/if}

        {if !empty($errors)}
            <div class="alert alert-danger" role="alert">
                {include file="errors.tpl" errors=$errors}
            </div>
        {/if}

        <form class="text-center login-form-box" role="form" action="{page_path id='user_login'}" method="post">
            {CSRF_token}
            <input type="hidden" name="action" value="login" />
            <input type="hidden" name="HTTP_REFERER" value="{$HTTP_REFERER}" />
            <input type="hidden" name="QUERY_STRING" value="{$QUERY_STRING}" />
            <div class="form-group">
                <label class="sr-only" for="Username">[[Username]]</label>
                <input name="username" type="text" class="form-control" id="Username" placeholder="[[Username:raw]]">
            </div>
            <div class="form-group">
                <label class="sr-only" for="Password">[[Password]]</label>
                <input name="password" type="password" class="form-control" id="Password" placeholder="[[Password:raw]]">
            </div>
            <div class="form-group">
                <button type="submit" class="default-button wb form-control">[[Login]]</button>
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

    </div>

</div>

