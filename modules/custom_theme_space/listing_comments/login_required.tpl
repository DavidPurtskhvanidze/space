<div class="loginRequestPage">
	<h1>[[Add Listing Comment]]</h1>
	{assign var="url" value={page_path id='user_registration'}}
	{capture assign="titleLogin"}
		[[Please log in to place your comment. If you do not have an account, please <a href="$url">Register</a>.]]
	{/capture}
	{module name="users" function="login" HTTP_REFERER=$GLOBALS.site_url|cat:$returnBackUri WARNING=$titleLogin}
</div>
