<h1>[[Add Listing Comment]]</h1>
{assign var="url" value={page_path id='user_registration' app='FrontEnd'}}
<div class="loginRequest">
	[[Please log in to place your comment. If you do not have an account, please <a href="$url">Register</a> (a full version of the website will be opened).]]
</div>
{module name="users" function="login" HTTP_REFERER=$GLOBALS.site_url|cat:$GLOBALS.current_page_uri}
