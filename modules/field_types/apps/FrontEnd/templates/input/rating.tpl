{if $object_sid}
	<span class="ratingValueAndControl">
		{strip}
			{section name=stars start=1 loop=6 step=1}
			<a href="{page_path id='rate'}?object_sid={$object_sid}&amp;object_type={$object_type}&amp;field_sid={$field_sid}&amp;rate={$smarty.section.stars.index}" rel="nofollow">
				{if $smarty.section.stars.index <= round($rating, 2)}
					<img height=25 src="{url file='main^star_full.png'}" alt="*" />
				{elseif $smarty.section.stars.index - $rating > 0 and $smarty.section.stars.index - $rating < 1}
					<img height=25 src="{url file='main^star_half.png'}" alt="+" />
				{else}
					<img height=25 src="{url file='main^star_empty.png'}" alt="_" />
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
{else}
	[[You will be able to edit rating after you created this {$object_type}.]]
{/if}
