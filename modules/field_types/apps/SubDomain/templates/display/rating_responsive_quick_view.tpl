{assign var=ratingValue value=$rating|string_format:"%.1f"}

{capture assign="current_page_url"}{$GLOBALS.site_url|cat:$GLOBALS.current_page_uri|cat:"?"|cat:$smarty.server.QUERY_STRING|urlencode}{/capture}
{$rating=round($rating)}
<span class="ratingValueAndControl">
		{section name=stars start=1 loop=6 step=1}
			{strip}
				{if $smarty.section.stars.index <= $rating}
					<span class="glyphicon glyphicon-star"></span>
				{else}
					<span class="glyphicon glyphicon-star-empty"></span>
				{/if}
			{/strip}
		{/section}
</span>

<span class="ratingReportToolTip">
	<span class="toolTipTail">&nbsp;</span>
	<span class="toolTipBody">
		[[$ratingValue / $count]]
	</span>
</span>
