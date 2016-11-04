<section class="login-request-page">
    {if $error eq 'NO_LISTING_PACKAGE_AVAILABLE'}
        {capture assign="contactAdminLink"}{page_path id='contact'}{/capture}
        <p class="alert alert-warning error" role="alert">
            [[There's no listing packages available on your membership plan]]. [[Please <a href="$contactAdminLink">contact</a> site administrator and report this issue. Thank you!]]
        </p>
    {elseif $error eq 'LISTINGS_NUMBER_LIMIT_EXCEEDED'}
        <p class="alert alert-warning error" role="alert">
            [[You've reached the limit of number of listings allowed by your plan]]
        </p>
    {elseif $error eq 'NO_CONTRACT'}
        <p class="alert alert-warning error" role="alert">
            [[Choose your memberhsip plan]]
        </p>
    {elseif $error eq 'NOT_LOGGED_IN'}
        {assign var="url" value={page_path id='user_registration'}}

        {capture assign="titleLogin"}
            [[Please log in to place a listing. If you do not have an account, please <a href="$url">Register</a>.]]
        {/capture}

        {module name="users" function="login" HTTP_REFERER=$GLOBALS.site_url|cat:$GLOBALS.current_page_uri WARNING=$titleLogin}
    {/if}
</section>
