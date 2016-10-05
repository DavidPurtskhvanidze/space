{assign var=ratingValue value=$rating|string_format:"%.1f"}

{capture assign="current_page_url"}{$GLOBALS.site_url|cat:$GLOBALS.current_page_uri|cat:"?"|cat:$smarty.server.QUERY_STRING|urlencode}{/capture}
{$rating=round($rating)}
<span class="ratingValueAndControl">
		{section name=stars start=1 loop=6 step=1}
			{strip}
			<a title="{$smarty.section.stars.index}" href="{page_path id='rate'}?object_sid={$object_sid}&amp;object_type={$object_type}&amp;field_sid={$field_sid}&amp;rate={$smarty.section.stars.index}&amp;HTTP_REFERER={$current_page_url}" rel="nofollow">
				{if $smarty.section.stars.index <= $rating}
					<span class="glyphicon glyphicon-star"></span>
				{else}
					<span class="glyphicon glyphicon-star-empty"></span>
				{/if}
			</a>
			{/strip}
		{/section}
</span>

<span class="ratingReportToolTip">
	<span class="toolTipTail">&nbsp;</span>
	<span class="toolTipBody">
		[[$ratingValue / $count votes]]
	</span>
</span>
