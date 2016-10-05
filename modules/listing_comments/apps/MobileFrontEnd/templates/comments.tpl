{foreach from=$comments item=comment}
<div class="comment"{if !$comment.published} style="background:{get_custom_setting id='hidden_comments_background_color' theme=$GLOBALS.current_theme}"{/if}>
	{if $comment.published.isFalse}<div class="hiddenCommentTooltip">[[This comment has not been published and is currently visible only to its author and the owner of the listing.]]</div>{/if}
	<a name="comment{$comment.sid}" id="comment{$comment.sid}"></a>
	{if !$comment.published}<div class="hiddenCommentTooltip">[[This comment has not been published and is currently visible only to its author and the owner of the listing.]]</div>{/if}
	<div>{include file="miscellaneous^rating.tpl" rating=$comment.ListingRating}, [[$comment.posted]]</div>
	<div>
		[[By]]
		{if $comment.user.isNotEmpty}
			<b>{$comment.user.username}</b>
		{else}
			<b>[[admin]]</b>
		{/if}
	</div>
	<div>{$comment.comment}</div>
	<div>
		{assign var='current_uri' value=$GLOBALS.current_page_uri|cat:'?'|cat:$smarty.server.QUERY_STRING}
		{assign var='current_uri' value=$current_uri|urlencode}
		<a href="{page_path id='comment_add'}?listingSid={$comment.listing_sid}&amp;commentSid={$comment.sid}&amp;returnBackUri={$current_uri}">[[Reply to the Comment]]</a>
		{assign var="numberOfReplies" value=$comment.numberOfReplies}
		{if $numberOfReplies > 0}
		<div class="replies">{module name="listing_comments" function="display_comments" results_template="comments.tpl" QUERY_STRING="parent_comment_sid[equal]="|cat:$comment.sid|cat:"&listing_sid[equal]="|cat:$comment.listing_sid}</div>
		{/if}
	</div>
</div>
{/foreach}
