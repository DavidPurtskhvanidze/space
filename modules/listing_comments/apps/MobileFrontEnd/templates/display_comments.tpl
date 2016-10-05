{assign var='current_uri' value=$GLOBALS.current_page_uri|cat:'?'|cat:$smarty.server.QUERY_STRING}
{assign var='current_uri' value=$current_uri|urlencode}
<div class="listingDetails">
	<div class="comments">
		<h1>[[Listing Comments for]] "{$listing}"</h1>
		<a href="{page_path id='comment_add'}?listingSid={$listing.sid}&amp;returnBackUri={$current_uri}">[[Add your comment.]]</a>
		{display_success_messages}
		{if $listing.numberOfComments > 0}
			{module name="listing_comments" function="display_comments" results_template="comments.tpl" QUERY_STRING="listing_sid[equal]="|cat:$listing.sid}
		{else}
			[[There are no comments for this listing]]
		{/if}
	</div>
	{include file="classifieds^category_templates/display/subpages_links.tpl" currentPageId="comments" listing=$listing}
	{include file="classifieds^category_templates/display/search_results_controls.tpl"}
</div>
