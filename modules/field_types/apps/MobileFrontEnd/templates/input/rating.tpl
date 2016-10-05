{if $hasError}<div class="error validation">{$error}</div>{/if}
{if $object_sid}
	<nobr>
	{section name=stars start=1 loop=6 step=1}
	<a href="{page_path id='rate'}?object_sid={$object_sid}&object_type={$object_type}&field_sid={$field_sid}&rate={$smarty.section.stars.index}" rel="nofollow">
	{if $smarty.section.stars.index <= round($rating, 2)}
	<img height=25 src="{url file='main^star_full.gif'}" alt="" />
	{elseif $smarty.section.stars.index - $rating > 0 and $smarty.section.stars.index - $rating < 1}
	<img height=25 src="{url file='main^star_half.gif'}" alt="" />
	{else}
	<img height=25 src="{url file='main^star_empty.gif'}" alt="" />
	{/if}
	</a>
	{/section}
	</nobr>
	{assign var=ratingValue value=$rating|string_format:"%.1f"}
	<br>[[rating <b>$ratingValue</b> from $count votes]]<br>
{else}
	[[You will be able to edit rating after you created this {$object_type}.]]
{/if}
