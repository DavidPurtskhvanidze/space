{capture assign="current_page_url"}{$GLOBALS.site_url|cat:$GLOBALS.current_page_uri|cat:"?"|cat:$smarty.server.QUERY_STRING|urlencode}{/capture}
<span class="ratingValueAndControl">
	{strip}
		{section name=stars start=1 loop=6 step=1}
			<a href="{page_path id='rate'}?object_sid={$object_sid}&amp;object_type={$object_type}&amp;field_sid={$field_sid}&amp;rate={$smarty.section.stars.index}&amp;HTTP_REFERER={$current_page_url}" rel="nofollow">
				{if $smarty.section.stars.index <= round($rating, 2)}
					<img src="{url file='main^star_full.png'}" alt="*" />
				{elseif $smarty.section.stars.index - $rating > 0 and $smarty.section.stars.index - $rating < 1}
					<img src="{url file='main^star_half.png'}" alt="+" />
				{else}
					<img src="{url file='main^star_empty.png'}" alt="_" />
				{/if}
			</a>
		{/section}
	{/strip}
</span>
{assign var=ratingValue value=$rating|string_format:"%.1f"}
<span class="ratingReportToolTip">
	<span class="toolTipTail">&nbsp;</span>
	<span class="toolTipBody">
		[[$ratingValue / $count votes]]
	</span>
</span>
