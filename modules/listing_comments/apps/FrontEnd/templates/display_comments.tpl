<p>
	<a href="{page_path id='listing'}{$listingSid}/">[[Back to the Listing]]</a> |
	{assign var='current_uri' value=$GLOBALS.current_page_uri|cat:'?'|cat:$smarty.server.QUERY_STRING}
	{assign var='current_uri' value=$current_uri|urlencode}
	<a href="{page_path id='comment_add'}?listingSid={$listingSid}&amp;commentSid={$commentSid}&amp;returnBackUri={$current_uri}&amp;activeTab=commentsBlock">[[Add a Comment]]</a>
</p>

<h1>[[Comments]]</h1>

<p>[[Below are comments on listing #$listingSid, $listing]]</p>

{if $messages}{include file="message.tpl"}{/if}

{include file="comments.tpl"}
