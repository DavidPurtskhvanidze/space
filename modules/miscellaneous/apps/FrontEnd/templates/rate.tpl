<br />
{include file='classifieds^errors.tpl'}
{assign var='registrationUrl' value={page_path id='user_registration'}}
{capture assign="current_page_url"}{$GLOBALS.site_url|cat:$GLOBALS.current_page_uri|cat:"?"|cat:$smarty.server.QUERY_STRING|urlencode}{/capture}
{assign var='loginUrl' value={page_path id='user_login'}|cat:"?HTTP_REFERER="|cat:$current_page_url}
[[In order for you to vote for this {$object_type}, you need to have an account and be logged in. Please go ahead and <a href="$registrationUrl">Register<a/> or <a href="$loginUrl">Sign In</a>]]
<br />
{$current_rate}
<br />
<a href="{$HTTP_REFERER}">[[back]]</a>
