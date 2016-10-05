<div class="authorizationControls">
	{if $GLOBALS.current_user.logged_in}
		<div class="user">
			<span class="userNameWithLogo">{$GLOBALS.current_user.user_name}</span>
		</div>
		<div class="delimiter">|</div>
		<div class="logout"><a href="{page_path id='user_logout'}">[[Log Out]]</a></div>
	{/if}
</div>
<div class="footerRedirectLinks">
	<div class="mobileWebsite">[[Mobile Website]]</div>
	<div class="delimiter">|</div>
	<div class="mainWebsite"><a href="{page_path module='main' function='redirect_to_front_end'}">[[Main Website]]</a></div>
</div>
