{if $thirdPartyLoginIsSetUp}
	{assign var="HTTP_REFERER" value=$HTTP_REFERER|urlencode}
	{assign var="QUERY_STRING" value=$QUERY_STRING|urlencode}
	{assign var="current_page_uri" value=$GLOBALS.current_page_uri|urlencode}
	<div class="thirdPartyAuth">
		[[Or login using a third-party account]]:
		<div class="providerLinks">
			{if $googleIsSetUp}
				<a href="{page_path id='user_openid_oauth_login'}?provider=Google&HTTP_REFERER={$HTTP_REFERER}&QUERY_STRING={$QUERY_STRING}&current_page_uri={$current_page_uri}">
					<img src="{url file='third_party_auth_icons/icon_google.png'}" alt="Google" title="Google" /></a>
			{/if}
			{if $facebookIsSetUp}
				<a href="{page_path id='user_openid_oauth_login'}?provider=Facebook&HTTP_REFERER={$HTTP_REFERER}&QUERY_STRING={$QUERY_STRING}&current_page_uri={$current_page_uri}">
					<img src="{url file='third_party_auth_icons/icon_facebook.png'}" alt="Facebook" title="Facebook" /></a>
			{/if}
			{if $twitterIsSetUp}
				<a href="{page_path id='user_openid_oauth_login'}?provider=Twitter&HTTP_REFERER={$HTTP_REFERER}&QUERY_STRING={$QUERY_STRING}&current_page_uri={$current_page_uri}">
					<img src="{url file='third_party_auth_icons/icon_twitter.png'}" alt="Twitter" title="Twitter" /></a>
			{/if}
			<a href="{page_path id='user_openid_oauth_login'}?provider=Yahoo&HTTP_REFERER={$HTTP_REFERER}&QUERY_STRING={$QUERY_STRING}&current_page_uri={$current_page_uri}">
				<img src="{url file='third_party_auth_icons/icon_yahoo.png'}" alt="Yahoo" title="Yahoo" /></a>
			<a href="#" onclick="$(this).parent().parent().find('.openIdProviderForm').toggle('fast'); return false;">
				<img src="{url file='third_party_auth_icons/icon_openid.png'}" alt="OpenId" title="OpenId" /></a>
		</div>
		<div class="openIdProviderForm" id="openIdProviderForm">
			<form action="{page_path id='user_openid_oauth_login'}">
				<input type="hidden" name="provider" value="openId"/>
				<input type="hidden" name="HTTP_REFERER" value="{$HTTP_REFERER}"/>
				<input type="hidden" name="QUERY_STRING" value="{$QUERY_STRING}"/>
				<input type="hidden" name="uri" value="{$current_page_uri}"/>
				<input type="text" name="openIdUrl" class="form-control">
				<input type="submit" class="btn btn-default" value="[[Login:raw]]"/>
			</form>
		</div>
	</div>
{else}
	<!-- Error: user group for third party login is not set -->
{/if}

{require component="jquery" file="jquery.js"}
