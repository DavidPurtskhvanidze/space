{if $thirdPartyLoginIsSetUp}
	{assign var="HTTP_REFERER" value=$HTTP_REFERER|urlencode}
	{assign var="QUERY_STRING" value=$QUERY_STRING|urlencode}
	{assign var="current_page_uri" value=$GLOBALS.current_page_uri|urlencode}
	<div class="thirdPartyAuth text-center">
		[[Or login using a third-party account]]:
		<div class="providerLinks">
			{if $googleIsSetUp}
				<a href="{page_path id='user_openid_oauth_login'}?provider=Google&HTTP_REFERER={$HTTP_REFERER}&QUERY_STRING={$QUERY_STRING}&current_page_uri={$current_page_uri}">
				<span class="google">
					    <i class="fa fa-google-plus" title="Google"></i>
                </span>
                </a>
			{/if}
			{if $facebookIsSetUp}
				<a href="{page_path id='user_openid_oauth_login'}?provider=Facebook&HTTP_REFERER={$HTTP_REFERER}&QUERY_STRING={$QUERY_STRING}&current_page_uri={$current_page_uri}">
                    <span class="facebook">
					    <i class="fa fa-facebook-f" title="Facebook"></i>
                    </span>
                </a>
			{/if}
			{if $twitterIsSetUp}
				<a href="{page_path id='user_openid_oauth_login'}?provider=Twitter&HTTP_REFERER={$HTTP_REFERER}&QUERY_STRING={$QUERY_STRING}&current_page_uri={$current_page_uri}">
                    <span class="twitter">
                        <i class="fa fa-twitter" title="Twitter"></i>
                    </span>
                </a>
			{/if}
			<a href="{page_path id='user_openid_oauth_login'}?provider=Yahoo&HTTP_REFERER={$HTTP_REFERER}&QUERY_STRING={$QUERY_STRING}&current_page_uri={$current_page_uri}">
                <span class="yahoo">
                    <i class="fa fa-yahoo" title="Yahoo"></i>
                </span>
            </a>
			<a href=#" onclick="$(this).parent().parent().find('.openIdProviderForm').toggle('fast'); return false;">
                <span class="openId">
                    <i class="fa fa-openid" title="OpenId"></i>
                </span>
            </a>
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
