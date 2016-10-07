<div class="container">
    {assign var='currentUserName' value=$GLOBALS.current_user.user_name}
    {assign var='logoutLink' value={page_path id='user_logout'}}
    <p>[[You have already logged in as $currentUserName]].
        [[Click <a href="$logoutLink">here</a> to log out]].
    </p>
</div>