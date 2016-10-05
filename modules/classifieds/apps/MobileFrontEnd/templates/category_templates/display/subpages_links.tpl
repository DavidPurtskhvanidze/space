{assign var="uriParametersPart" value=$listing.id|cat:"/"}
{if !empty($listing_search)}
	{assign var="uriParametersPart" value=$uriParametersPart|cat:"?searchId="|cat:$listing_search.id}
	{assign var="searchResultsUrl" value=$listing_search.search_results_uri|cat:"?action=restore&searchId="|cat:$listing_search.id}
{/if}
{assign var="returnBackUrl" value="{page_uri id='listing'}"|cat:$uriParametersPart}
<div class="subpagesLinks">
	<ul>
		<li>
			{if $listing.saved.isTrue}
				<span class="listingIsSaved">[[Listing is saved]]</span>
			{else}
				{assign var="returnBackUrl" value="{page_uri id='listing'}"|cat:$uriParametersPart}
				<a href="{page_path id='listing_save'}?listing_id={$listing.id}&returnBackUrl={$returnBackUrl|urlencode}">[[Save Listing]]</a>
			{/if}
		</li>
		{if $currentPageId != 'details'}
			<li>
				<a href="{page_path id='listing'}{$uriParametersPart}">[[Details]]<span class="bullet">»</span></a>
			</li>
		{/if}
		{if $currentPageId != 'video' && ($listing.Video.file_url.isNotEmpty || ($listing.feature_youtube_video_id.exists && $listing.feature_youtube_video_id.isNotEmpty))}
			<li>
				<a href="{$GLOBALS.site_url}/listing/video/{$uriParametersPart}">[[Video]]<span class="bullet">»</span></a>
			</li>
		{/if}
		{if $listing.pictures.numberOfItems > 0 && $currentPageId != 'pictures'}
			<li>
				{if $listing.pictures.numberOfItems>1}
					<a href="{page_path id='listing_pictures'}{$uriParametersPart}">[[Pictures]]<span class="bullet">»</span></a>
				{else}
					<a href="{$GLOBALS.site_url}/listing/picture/{$uriParametersPart}">[[Pictures]]<span class="bullet">»</span></a>
				{/if}
			</li>
		{/if}
		{if $currentPageId != 'map'}
			<li>
				<a href="{$GLOBALS.site_url}/listing/map/{$uriParametersPart}">[[Map]]<span class="bullet">»</span></a>
			</li>
		{/if}
		{module name="listing_comments" function="display_comment_control" listing=$listing controll="DISPLAY_COMMENTS_ON_SUBPAGE_MENU" wrapperTemplate='comment_controll_ul_wrapper.tpl' currentPageId=$currentPageId}
		{if $currentPageId != 'seller-info'}
			<li>
				<a href="{$GLOBALS.site_url}/listing/seller-info/{$uriParametersPart}">[[Seller Info]]<span class="bullet">»</span></a>
			</li>
		{/if}
		<li>
			<a href="{page_path id='contact_seller'}?listing_id={$listing.id}&returnBackUrl={$returnBackUrl|urlencode}{if $searchResultsUrl}&searchResultsUrl={$searchResultsUrl|urlencode}{/if}">[[Contact seller]]<span class="bullet">»</span></a>
		</li>
		<li>
			<a href="{$GLOBALS.site_url}/tell-a-friend/?listing_id={$listing.id}&returnBackUrl={$returnBackUrl|urlencode}&listing_title={$listing|cat:""|strip_tags:false|urlencode}">[[Tell a Friend]]<span class="bullet">»</span></a>
		</li>
		<li>
			<a href="{page_path id='user_saved_listings'}">[[Saved Listings]]<span class="bullet">»</span></a>
		</li>
	</ul>
</div>
